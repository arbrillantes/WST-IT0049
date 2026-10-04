<?php

// Run: php tests/smoke.php [local-base-url]
// Creates uniquely named test records and removes only those records in finally.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$base = rtrim($argv[1] ?? 'http://localhost/Brillantes/IT0049/demoelectric', '/');
if (! in_array(parse_url($base, PHP_URL_HOST), ['localhost', '127.0.0.1'], true)) {
    throw new RuntimeException('Smoke tests only run against localhost.');
}

$db = new mysqli('127.0.0.1', getenv('DEMO_DB_USER') ?: 'root', getenv('DEMO_DB_PASSWORD') ?: '', 'electric_company', (int) (getenv('DEMO_DB_PORT') ?: 3306));
$db->set_charset('utf8mb4');
$suffix = bin2hex(random_bytes(6));
$email = 'smoke.' . $suffix . '@example.test';
$accountNumber = 'SMOKE-' . $suffix;
$accountEmail = 'account.' . $suffix . '@example.test';
$password = 'Smoke-' . bin2hex(random_bytes(12));
$client = curl_init();
curl_setopt_array($client, [CURLOPT_COOKIEFILE => '', CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15]);
$passed = 0;

function check(bool $condition, string $message): void
{
    global $passed;
    if (! $condition) {
        throw new RuntimeException($message);
    }
    $passed++;
    echo "PASS $message\n";
}

function request(string $path, ?array $post = null): array
{
    global $base, $client;
    $headers = [];
    curl_setopt_array($client, [
        CURLOPT_URL => $base . '/' . ltrim($path, '/'),
        CURLOPT_HEADERFUNCTION => static function ($curl, string $line) use (&$headers): int {
            if (str_contains($line, ':')) {
                [$key, $value] = explode(':', $line, 2);
                $headers[strtolower(trim($key))] = trim($value);
            }
            return strlen($line);
        },
    ]);
    if ($post !== null) {
        curl_setopt_array($client, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query($post)]);
    } else {
        curl_setopt($client, CURLOPT_HTTPGET, true);
    }
    $body = curl_exec($client);
    if ($body === false) {
        throw new RuntimeException(curl_error($client));
    }

    return ['status' => curl_getinfo($client, CURLINFO_RESPONSE_CODE), 'body' => $body, 'headers' => $headers];
}

function postForm(string $formPath, string $postPath, array $data): array
{
    $page = request($formPath);
    if (! preg_match('/name="csrf_test_name" value="([^"]+)"/', $page['body'], $match)) {
        throw new RuntimeException('Missing CSRF field on ' . $formPath . ' (HTTP ' . $page['status'] . ')');
    }
    return request($postPath, $data + ['csrf_test_name' => $match[1]]);
}

function redirectsTo(array $response, string $path): bool
{
    global $base;
    return in_array($response['status'], [302, 303], true)
        && ($response['headers']['location'] ?? '') === $base . $path;
}

function rowIds(array $response): array
{
    preg_match_all('~href="[^"]*/account/([0-9]+)"~', $response['body'], $matches);
    return array_values(array_unique($matches[1]));
}

function testAccount(): ?array
{
    global $db, $accountNumber;
    $query = $db->prepare('SELECT * FROM customer_accounts WHERE account_number = ?');
    $query->bind_param('s', $accountNumber);
    $query->execute();
    return $query->get_result()->fetch_assoc();
}

$initialCount = (int) $db->query('SELECT COUNT(*) AS n FROM customer_accounts')->fetch_assoc()['n'];
$initialAdminCount = (int) $db->query('SELECT COUNT(*) AS n FROM users')->fetch_assoc()['n'];
$exitCode = 0;
try {
    foreach (['', 'about', 'services', 'contact', 'register', 'login', 'admin/login', 'assets/css/custom.css', 'assets/js/app.js'] as $path) {
        check(request($path)['status'] === 200, 'Public page/asset ' . ($path ?: 'home'));
    }
    foreach (['admin/dashboard', 'account/new', 'account/1', 'account/1/edit', 'account/1/delete'] as $path) {
        check(redirectsTo(request($path), '/admin/login'), 'Guest blocked: ' . $path);
    }
    check(redirectsTo(postForm('login', 'account', []), '/admin/login'), 'Guest cannot create with a valid CSRF token');
    check(redirectsTo(postForm('login', 'account/1/update', []), '/admin/login'), 'Guest cannot update');
    check(redirectsTo(postForm('login', 'account/1/delete', []), '/admin/login'), 'Guest cannot delete');

    $registration = [
        'first_name' => 'Smoke', 'last_name' => 'Tester', 'email' => $email,
        'phone' => '09123456789', 'address' => '123 Test Street', 'city' => 'Manila',
        'state' => 'Metro Manila', 'zip_code' => '1000', 'password' => $password,
        'confirm_password' => $password, 'terms' => 'on',
        'user_type' => 'admin', 'username' => 'admin', // Malicious signup fields must be ignored.
    ];
    check(redirectsTo(postForm('register', 'register', array_replace($registration, ['confirm_password' => 'wrong'])), '/register'), 'Registration rejects mismatched passwords');
    $response = postForm('register', 'register', $registration);
    check(redirectsTo($response, '/login'), 'Registration saves and redirects to login');
    $query = $db->prepare('SELECT * FROM customer_accounts WHERE email = ?');
    $query->bind_param('s', $email);
    $query->execute();
    $user = $query->get_result()->fetch_assoc();
    check($user !== null && password_verify($password, $user['password']) && $password !== $user['password'], 'Password stored as a hash');
    check(redirectsTo(postForm('register', 'register', $registration), '/register'), 'Duplicate registration rejected');

    check(redirectsTo(postForm('login', 'login', ['email' => $email, 'password' => 'wrong']), '/login'), 'Invalid password rejected');
    check(redirectsTo(request('admin/dashboard'), '/admin/login'), 'Failed login cannot access dashboard');
    check($user !== null && ! array_key_exists('user_type', $user) && ! array_key_exists('username', $user), 'Customer registration has no admin role or username');
    check((int) $db->query('SELECT COUNT(*) AS n FROM users')->fetch_assoc()['n'] === $initialAdminCount, 'Customer registration never writes to users');
    check(! empty($user['account_number']) && $user['customer_name'] === 'Smoke Tester', 'Registration creates the customer account directly');
    check(redirectsTo(postForm('login', 'login', ['email' => $email, 'password' => $password]), '/customer/dashboard'), 'Customer login redirects to own dashboard');
    check(redirectsTo(request('login'), '/customer/dashboard'), 'Logged-in customer login returns to own dashboard');
    check(redirectsTo(request('register'), '/customer/dashboard'), 'Logged-in customer registration returns to own dashboard');
    check(redirectsTo(request('dashboard'), '/customer/dashboard'), 'Old shared dashboard URL redirects customer correctly');
    $profile = request('customer/dashboard');
    check($profile['status'] === 200 && str_contains($profile['body'], $email), 'Customer sees own profile');
    check(! str_contains($profile['body'], 'EC-2024-') && ! str_contains($profile['body'], 'Add customer'), 'Customer page contains no management controls or other records');
    $profile = request('customer/dashboard?user_id=1&id=1');
    check(str_contains($profile['body'], $email) && ! str_contains($profile['body'], 'admin@puihaha.example'), 'Profile ignores supplied user IDs');

    foreach (['admin/dashboard', 'account/new', 'account/1', 'account/1/edit', 'account/1/delete'] as $path) {
        check(request($path)['status'] === 403, 'Customer denied admin GET: ' . $path);
    }
    foreach (['account', 'account/999999999/update', 'account/999999999/delete'] as $path) {
        check(postForm('customer/dashboard', $path, ['user_type' => 'admin'])['status'] === 403, 'Customer denied admin POST: ' . $path);
    }
    check(redirectsTo(postForm('customer/dashboard', 'logout', []), '/login'), 'Customer logout returns to customer login');
    check(redirectsTo(request('customer/dashboard'), '/login'), 'Customer profile requires login');

    check(request('admin/register')['status'] === 404, 'No admin registration page');
    check(postForm('admin/login', 'admin/register', [])['status'] === 404, 'No admin registration endpoint');
    $adminPage = request('admin/login');
    check(str_contains($adminPage['body'], 'name="username"') && str_contains($adminPage['body'], 'Customer Register') && ! str_contains($adminPage['body'], '/admin/register'), 'Admin page has username login and customer registration navigation only');
    preg_match('~<nav\\b.*?</nav>~s', $adminPage['body'], $nav);
    check(strpos($nav[0], '>Customer Register</a>') < strpos($nav[0], '>Login</a>') && strpos($nav[0], '>Login</a>') < strpos($nav[0], '>ADMIN LOGIN</a>') && str_contains($nav[0], 'admin-login-link'), 'Guest tabs ordered and admin login highlighted');
    check(redirectsTo(postForm('admin/login', 'admin/login', ['username' => 'admin', 'password' => 'wrong']), '/admin/login'), 'Bad admin password rejected');
    check(redirectsTo(postForm('admin/login', 'admin/login', ['username' => $email, 'password' => $password]), '/admin/login'), 'Customer credentials cannot use admin login');
    $adminPassword = getenv('DEMO_ADMIN_PASSWORD') ?: 'admin123';
    check(redirectsTo(postForm('login', 'login', ['email' => 'admin@puihaha.example', 'password' => $adminPassword]), '/login'), 'Admin credentials cannot use customer login');
    $admin = $db->query("SELECT * FROM users WHERE username = 'admin'")->fetch_assoc();
    check($admin !== null && $admin['user_type'] === 'admin' && password_verify($adminPassword, $admin['password']), 'Admin exists with hashed password');
    check(redirectsTo(postForm('admin/login', 'admin/login', ['username' => 'admin', 'password' => $adminPassword]), '/admin/dashboard'), 'Admin login redirects to management dashboard');
    check(redirectsTo(request('dashboard'), '/admin/dashboard'), 'Old shared dashboard URL redirects admin correctly');
    check(redirectsTo(request('admin/login'), '/admin/dashboard'), 'Logged-in admin returns to admin dashboard');
    check(request('customer/dashboard')['status'] === 403, 'Admin management session stays separate from customer page');

    $page1 = request('admin/dashboard');
    $page2 = request('admin/dashboard?page_accounts=2');
    check($page1['status'] === 200 && str_contains($page1['body'], 'Customer Accounts'), 'Dashboard renders');
    $registered = request('admin/dashboard?search=' . urlencode($email));
    check(str_contains($registered['body'], $user['account_number']), 'Registered customer appears in admin customer list');
    check(str_contains($page1['headers']['cache-control'] ?? '', 'no-store'), 'Protected data is not cached');
    check(count(rowIds($page1)) === 10 && count(rowIds($page2)) === 10 && array_intersect(rowIds($page1), rowIds($page2)) === [], 'Pagination has 10 distinct customers per page');
    $filter = request('admin/dashboard?search=Industries&status=suspended&type=industrial');
    check(count(rowIds($filter)) === 1 && str_contains($filter['body'], 'Heavy Industries Ltd'), 'Search, status, and type combine');
    $filter = request('admin/dashboard?search=EC-2024&status=active&type=residential');
    check(str_contains($filter['body'], 'page_accounts=2') && str_contains($filter['body'], 'search=EC-2024') && str_contains($filter['body'], 'type=residential'), 'Pagination retains filters');
    $empty = request('admin/dashboard?search=not-found-' . $suffix);
    check($empty['status'] === 200 && str_contains($empty['body'], 'No customer accounts found.'), 'Empty result renders');
    check(request('account/999999999')['status'] === 404, 'Unknown account returns 404');

    $data = [
        'account_number' => $accountNumber, 'customer_name' => '<script>alert(1)</script>',
        'address' => '321 Test Avenue', 'phone' => '09123456789', 'email' => $accountEmail,
        'meter_number' => 'MTR-TEST', 'connection_type' => 'residential', 'status' => 'active',
    ];
    check(redirectsTo(postForm('account/new', 'account', array_replace($data, ['email' => 'invalid', 'status' => 'unknown'])), '/account/new'), 'Invalid email and status rejected');
    check(testAccount() === null, 'Invalid create does not write to database');
    $created = postForm('account/new', 'account', $data);
    $record = testAccount();
    check($record !== null && redirectsTo($created, '/account/' . $record['id']), 'Create customer persists and redirects to detail');
    $id = (int) $record['id'];
    $detail = request('account/' . $id);
    check($detail['status'] === 200 && str_contains($detail['body'], '&lt;script&gt;') && ! str_contains($detail['body'], '<script>alert(1)</script>'), 'Customer text is escaped');
    check(redirectsTo(postForm('account/new', 'account', $data), '/account/new'), 'Duplicate account number rejected');

    request('account/' . $id . '/delete', []);
    check(testAccount() !== null, 'Delete without CSRF token blocked');
    check(request('account/' . $id . '/delete')['status'] === 200 && testAccount() !== null, 'GET delete only shows confirmation');
    check(request('account/' . $id . '/update')['status'] === 404, 'GET cannot update a customer');

    $duplicateEdit = array_replace($data, ['account_number' => 'EC-2024-0001']);
    check(redirectsTo(postForm('account/' . $id . '/edit', 'account/' . $id . '/update', $duplicateEdit), '/account/' . $id . '/edit'), 'Update rejects another customer account number');
    check(testAccount()['customer_name'] === $data['customer_name'], 'Rejected update leaves record unchanged');
    $data['customer_name'] = 'Updated Smoke Customer';
    $data['status'] = 'inactive';
    check(redirectsTo(postForm('account/' . $id . '/edit', 'account/' . $id . '/update', $data), '/account/' . $id), 'Update saves with own account number');
    check(testAccount()['customer_name'] === 'Updated Smoke Customer' && testAccount()['status'] === 'inactive', 'Updated fields persisted');
    check(redirectsTo(postForm('account/' . $id . '/delete', 'account/' . $id . '/delete', []), '/admin/dashboard'), 'Confirmed delete returns to dashboard');
    check(testAccount() === null && request('account/' . $id)['status'] === 404, 'Deleted customer no longer exists');

    $cookiesBeforeLogout = curl_getinfo($client, CURLINFO_COOKIELIST);
    check(redirectsTo(postForm('admin/dashboard', 'logout', []), '/admin/login'), 'Logout redirects to login');
    check(redirectsTo(request('admin/dashboard'), '/admin/login'), 'Dashboard blocked after logout');
    curl_setopt($client, CURLOPT_COOKIELIST, 'ALL');
    foreach ($cookiesBeforeLogout as $cookie) {
        curl_setopt($client, CURLOPT_COOKIELIST, $cookie);
    }
    check(redirectsTo(request('admin/dashboard'), '/admin/login'), 'Old session cannot be replayed after logout');
    check((int) $db->query('SELECT COUNT(*) AS n FROM customer_accounts')->fetch_assoc()['n'] === $initialCount + 1, 'Only new registered customer remains before test cleanup');
} catch (Throwable $exception) {
    $exitCode = 1;
    fwrite(STDERR, 'FAIL ' . $exception->getMessage() . "\n");
} finally {
    // Exact random identifiers ensure no existing user/customer record is removed.
    $cleanup = $db->prepare('DELETE FROM customer_accounts WHERE account_number = ? AND email = ?');
    $cleanup->bind_param('ss', $accountNumber, $accountEmail);
    $cleanup->execute();
    $cleanup = $db->prepare('DELETE FROM customer_accounts WHERE email = ?');
    $cleanup->bind_param('s', $email);
    $cleanup->execute();
    check((int) $db->query('SELECT COUNT(*) AS n FROM customer_accounts')->fetch_assoc()['n'] === $initialCount, 'Existing customer count preserved after cleanup');
    check((int) $db->query("SELECT COUNT(*) AS n FROM users WHERE user_type <> 'admin'")->fetch_assoc()['n'] === 0, 'Users table contains admins only');
    curl_close($client);
    echo "$passed checks passed. Temporary test records removed.\n";
}
exit($exitCode);
