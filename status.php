<?php
// Handle form submission
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['instagram_url'])) {
    $url = trim($_POST['instagram_url']);
    
    // Basic URL validation
    if (preg_match('/https:\/\/www\.instagram\.com\/(p|reel)\/[A-Za-z0-9_-]+/', $url)) {
        $api_url = 'https://its-smart-dev.vercel.app/download?url=' . urlencode($url);
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Adjust based on your security needs
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response && $http_code === 200) {
            $result = json_decode($response, true);
        } else {
            $result = [
                'status' => 'error',
                'message' => 'API is down, please try again later! 😔'
            ];
        }
    } else {
        $result = [
            'status' => 'error',
            'message' => 'Please enter a valid Instagram post or reel URL! 😔'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SmartDev's Instagram Scraper API - Extract media URLs from Instagram posts with ease.">
    <meta property="og:title" content="SmartDev's Instagram Scraper API">
    <meta property="og:description" content="Fast, reliable API to scrape Instagram media URLs. Try it now!">
    <title>SmartDev's Insta Scraper API</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-bg: #ffffff;
            --secondary-bg: #f8f9fa;
            --accent-color: #28a745;
            --text-color: #333333;
            --hover-scale: 1.05;
            --shadow-color: rgba(0, 0, 0, 0.2);
        }

        [data-theme="dark"] {
            --primary-bg: #0a0e17;
            --secondary-bg: #1a1f2e;
            --accent-color: #00ff88;
            --text-color: #d4d4d8;
            --shadow-color: rgba(0, 0, 0, 0.7);
        }

        body {
            background-color: var(--primary-bg);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
            transition: background-color 0.3s, color 0.3s;
            position: relative;
            overflow-x: hidden;
        }

        .hero {
            background: linear-gradient(to right, #e8f5e9, #c8e6c9);
            padding: 120px 0;
            text-align: center;
            border-bottom: 2px solid var(--accent-color);
        }

        [data-theme="dark"] .hero {
            background: linear-gradient(to right, #1a1f2e, #2a3247);
            border-bottom: 2px solid var(--accent-color);
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-color);
            text-shadow: 2px 2px 4px var(--shadow-color);
        }

        .hero p {
            font-size: 1.6rem;
            color: var(--text-color);
        }

        .navbar {
            background-color: var(--secondary-bg);
            box-shadow: 0 2px 8px var(--shadow-color);
            transition: background-color 0.3s;
        }

        .navbar.scrolled {
            background-color: var(--primary-bg);
        }

        .navbar .nav-link {
            color: var(--text-color);
            font-weight: 400;
            transition: color 0.3s, transform 0.3s;
        }

        [data-theme="dark"] .navbar .nav-link {
            color: var(--text-color);
        }

        .navbar .nav-link:hover {
            color: var(--accent-color);
            transform: translateY(-2px);
        }

        section {
            padding: 80px 0;
        }

        h2 {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 30px;
            position: relative;
        }

        h2::after {
            content: '';
            width: 50px;
            height: 3px;
            background: var(--accent-color);
            position: absolute;
            bottom: -10px;
            left: 0;
        }

        .btn-custom, .btn-primary {
            background-color: var(--accent-color);
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            transition: transform 0.2s, box-shadow 0.2s, background-color 0.3s;
        }

        .btn-custom:hover, .btn-primary:hover {
            transform: scale(var(--hover-scale));
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            background-color: #218838;
        }

        [data-theme="dark"] .btn-custom:hover, [data-theme="dark"] .btn-primary:hover {
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.4);
            background-color: #00cc70;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px var(--shadow-color);
            transition: transform 0.3s;
            background-color: var(--primary-bg);
            color: var(--text-color);
        }

        [data-theme="dark"] .card {
            background-color: var(--primary-bg);
            color: var(--text-color);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .accordion-button {
            background-color: var(--secondary-bg);
            color: var(--text-color);
            font-weight: 500;
            transition: background-color 0.3s, color 0.3s;
        }

        [data-theme="dark"] .accordion-button {
            background-color: var(--secondary-bg);
            color: var(--text-color);
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--accent-color);
            color: #ffffff;
        }

        .accordion-body {
            background-color: var(--primary-bg);
            border: 1px solid #dee2e6;
            color: var(--text-color);
        }

        [data-theme="dark"] .accordion-body {
            border: 1px solid #3a3f4e;
            color: var(--text-color);
        }

        .input-group input {
            transition: box-shadow 0.3s, border-color 0.3s;
        }

        .input-group input:focus {
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
            border-color: var(--accent-color);
        }

        .input-group input.is-valid {
            border-color: var(--accent-color);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3E%3Cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .input-group input.is-invalid {
            border-color: #dc3545;
        }

        .btn-copy {
            transition: all 0.3s;
        }

        .btn-copy:hover {
            background: var(--accent-color);
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
        }

        .text-accent {
            color: var(--accent-color);
            transition: color 0.3s;
        }

        .text-accent:hover {
            color: #1e7e34;
        }

        [data-theme="dark"] .text-accent:hover {
            color: #00cc70;
        }

        .fa-instagram, .fa-telegram, .fa-github {
            font-size: 1.2rem;
            margin-right: 5px;
        }

        .glow-text {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        #progress-bar {
            height: 4px;
            background: var(--accent-color);
            transition: width 0.3s ease-in-out;
        }

        .starry-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.5s;
        }

        [data-theme="dark"] .starry-bg {
            opacity: 0.3;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle 2s infinite alternate;
        }

        @keyframes twinkle {
            0% { opacity: 0.3; }
            100% { opacity: 1; }
        }

        footer {
            background-color: var(--secondary-bg);
            padding: 30px 0;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        [data-theme="dark"] footer {
            border-top: 1px solid #3a3f4e;
        }

        footer p {
            color: var(--text-color);
        }

        .social-icons a {
            font-size: 2rem;
            margin: 0 15px;
            transition: transform 0.3s, opacity 0.3s;
        }

        .social-icons a:hover {
            transform: scale(1.2);
            opacity: 0.8;
        }

        .fa-facebook-f { color: #1877f2; }
        .fa-github { color: #181717; }
        [data-theme="dark"] .fa-github { color: #d4d4d8; }
        .fa-telegram { color: #0088cc; }
        .fa-instagram { color: #e4405f; }
        .fa-twitter { color: #1da1f2; }

        #back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: none;
            background-color: var(--accent-color);
            color: #ffffff;
            border: none;
            padding: 12px 18px;
            border-radius: 50%;
            transition: transform 0.2s, opacity 0.3s;
        }

        #back-to-top:hover {
            transform: scale(1.1);
            opacity: 0.9;
        }

        .theme-toggle {
            background-color: transparent;
            border: none;
            color: var(--text-color);
            font-size: 1.2rem;
            padding: 8px;
            transition: color 0.3s, transform 0.3s;
            display: flex;
            align-items: center;
        }

        .theme-toggle:hover {
            color: var(--accent-color);
            transform: scale(1.2);
        }

        .theme-toggle .fa-sun { display: inline; }
        .theme-toggle .fa-moon { display: none; }
        [data-theme="dark"] .theme-toggle .fa-sun { display: none; }
        [data-theme="dark"] .theme-toggle .fa-moon { display: inline; }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1.2rem; }
            section { padding: 50px 0; }
            .social-icons a { font-size: 1.5rem; margin: 0 10px; }
            .theme-toggle { font-size: 1rem; padding: 6px; }
        }
    </style>
</head>
<body>
    <!-- Starry Background for Dark Mode -->
    <div class="starry-bg" id="starry-bg"></div>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <h1>SmartDev's Insta Scraper API <i class="fab fa-instagram me-2 text-accent"></i></h1>
            <p>Extract direct media URLs from Instagram posts with ease and style! ✨</p>
            <a href="#try-it" class="btn btn-custom mt-4">Try It Now</a>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Insta Scraper API <i class="fab fa-instagram me-2 text-accent"></i></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#try-it">Try It</a></li>
                    <li class="nav-item"><a class="nav-link" href="#docs">Docs</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contribute">Contribute</a></li>
                    <li class="nav-item">
                        <button id="theme-toggle" class="btn theme-toggle" aria-label="Toggle theme">
                            <i class="fas fa-sun"></i>
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Try It Section -->
    <section id="try-it" class="container" data-aos="fade-up">
        <h2>Try It Out 🌟</h2>
        <div class="card p-4">
            <p class="text-center text-success fs-5 mb-4">API Status: <span id="api-status" class="fw-bold">Live</span> ⚡️</p>
            <form id="api-form" class="input-group mb-3" method="POST" action="">
                <span class="input-group-text"><i class="fa-brands fa-instagram"></i></span>
                <input id="instagram-url" name="instagram_url" type="text" class="form-control" placeholder="Enter Instagram post or reel URL (e.g., https://www.instagram.com/p/XXXXX/ or https://www.instagram.com/reel/XXXXX/)" value="<?php echo isset($_POST['instagram_url']) ? htmlspecialchars($_POST['instagram_url']) : ''; ?>" required>
                <button id="submit-url" class="btn btn-primary" type="submit">Get Media URLs</button>
            </form>
            <div id="progress-bar" class="progress-bar" style="width: 0%;"></div>
            <div id="result" class="mt-3">
                <?php if ($result): ?>
                    <?php if ($result['status'] === 'success'): ?>
                        <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($result['message']); ?> 🌟</div>
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($result['title'] ?? 'No caption'); ?></p>
                        <p><strong>Author:</strong> <?php echo htmlspecialchars($result['author'] ?? 'Unknown'); ?></p>
                        <ul class="list-group">
                            <?php foreach ($result['media_urls'] as $url): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="text-accent text-decoration-none"><?php echo htmlspecialchars($url); ?></a>
                                    <button class="btn btn-sm btn-outline-success btn-copy" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy URL" onclick="copyToClipboard('<?php echo htmlspecialchars($url); ?>')">Copy</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($result['message']); ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Documentation Section -->
    <section id="docs" class="container" data-aos="fade-up">
        <h2>API Documentation 📚</h2>
        <div class="accordion" id="docsAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingApi">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseApi" aria-expanded="true" aria-controls="collapseApi">
                        GET /download - How It Works
                    </button>
                </h2>
                <div id="collapseApi" class="accordion-collapse collapse show" aria-labelledby="headingApi" data-bs-parent="#docsAccordion">
                    <div class="accordion-body">
                        <p>Send a GET request to <code>/download</code> with an Instagram post or reel URL as a query parameter.</p>
                        <pre>
<strong>Request Example:</strong>
curl -X GET "https://its-smart-dev.vercel.app/download?url=https://www.instagram.com/p/XXXXX/"
curl -X GET "https://its-smart-dev.vercel.app/download?url=https://www.instagram.com/reel/XXXXX/"

<strong>Response Example (Success):</strong>
{
  "status": "success",
  "message": "Media URLs extracted successfully.",
  "media_urls": [
    "https://instagram.com/.../media1.jpg",
    "https://instagram.com/.../media2.mp4"
  ],
  "title": "Post caption",
  "author": "username",
  "developer": "API Developer : @ISmartDevs",
  "channel": "Updates Channel : @TheSmartDev"
}

<strong>Response Example (Error):</strong>
{
  "status": "error",
  "message": "URL Required To Download Your Desired Media!",
  "media_urls": [],
  "title": null,
  "author": null,
  "developer": "API Developer : @ISmartDevs",
  "channel": "Updates Channel : @TheSmartDev"
}
                        </pre>
                        <p><strong>Note:</strong> Requires valid Instagram session cookies (<code>cookies.txt</code>) in Netscape format with <code>sessionid</code> and <code>csrftoken</code>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contribute Section -->
    <section id="contribute" class="container" data-aos="fade-up">
        <h2>Contribute 🌟</h2>
        <div class="card p-4">
            <p>Contributions are welcome! Follow these steps to contribute:</p>
            <ol>
                <li>Fork the repository. 🍴</li>
                <li>Create a new branch (<code>git checkout -b feature-branch</code>). 🌿</li>
                <li>Commit your changes (<code>git commit -m 'Add new feature'</code>). 💾</li>
                <li>Push to the branch (<code>git push origin feature-branch</code>). 🚀</li>
                <li>Open a Pull Request. 📬</li>
            </ol>
            <p>Check out the repository on <a href="https://github.com/TheSmartDevs/Insta-Scrapper-API" target="_blank" class="text-accent"><i class="fa-brands fa-github"></i> @TheSmartDevs</a>.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>Built with ❤️ by <a href="https://x.com/abirxdhackz" target="_blank" class="text-accent"><i class="fa-brands fa-instagram"></i> ISmartDevs</a></p>
        <p>Join our community at <a href="https://t.me/TheSmartDev" target="_blank" class="text-accent"><i class="fa-brands fa-telegram"></i> TheSmartDev</a></p>
        <p>This API is Open Source on <a href="https://github.com/TheSmartDevs/Insta-Scrapper-API" target='_blank' class="text-accent"><i class="fa-brands fa-github"></i> @TheSmartDevs</a></p>
        <p>© 2025 Instagram Post Scraper API | Powered by <a href="https://t.me/TheSmartDev" target="_blank" class="text-accent"><i class="fa-brands fa-telegram"></i> TheSmartDev</a></p>
    </footer>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="btn"><i class="fas fa-arrow-up"></i></button>

    <!-- GSAP for Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4b9aH0h67vO4z6e7FBaJZ6gn2xlt6lDOck'nrZPDurW8nW47SO7iAwd+rCE2+K3lQ2+Ve16PzfO6w==" crossorigin="anonymous"></script>
    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Confetti for Success Animation -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <!-- Client-side JavaScript -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Initialize Bootstrap Tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            themeToggle.querySelector('.fa-moon').style.display = 'inline';
            themeToggle.querySelector('.fa-sun').style.display = 'none';
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            themeToggle.querySelector('.fa-sun').style.display = 'inline';
            themeToggle.querySelector('.fa-moon').style.display = 'none';
        }

        themeToggle.addEventListener('click', () => {
            let theme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            if (theme === 'dark') {
                themeToggle.querySelector('.fa-moon').style.display = 'inline';
                themeToggle.querySelector('.fa-sun').style.display = 'none';
            } else {
                themeToggle.querySelector('.fa-sun').style.display = 'inline';
                themeToggle.querySelector('.fa-moon').style.display = 'none';
            }
            generateStars();
        });

        // Generate Starry Background for Dark Mode
        function generateStars() {
            const starryBg = document.getElementById('starry-bg');
            starryBg.innerHTML = '';
            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                for (let i = 0; i < 50; i++) {
                    const star = document.createElement('div');
                    star.className = 'star';
                    star.style.width = `${Math.random() * 3}px`;
                    star.style.height = star.style.width;
                    star.style.left = `${Math.random() * 100}%`;
                    star.style.top = `${Math.random() * 100}%`;
                    star.style.animationDelay = `${Math.random() * 2}s`;
                    starryBg.appendChild(star);
                }
            }
        }
        generateStars();

        // GSAP Animations with Fallback
        if (typeof gsap !== 'undefined') {
            gsap.from('.card', { opacity: 0, y: 50, duration: 1, ease: 'power3.out' });
            gsap.from('.fade-in', { opacity: 0, y: 20, duration: 1, delay: 0.5, stagger: 0.2 });
        } else {
            console.warn('GSAP not loaded; skipping animations.');
            document.querySelectorAll('.card').forEach(card => card.style.opacity = 1);
        }

        // Navbar Scroll Effect
        const navbar = document.querySelector('.navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Back to Top Button
        const backToTop = document.getElementById('back-to-top');
        window.onscroll = function() {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        };

        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard! ✨');
            }).catch(() => {
                alert('Failed to copy! 😔');
            });
        }

        // Trigger confetti on page load if successful result
        <?php if ($result && $result['status'] === 'success'): ?>
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        <?php endif; ?>
    </script>
</body>
</html>
