<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Creative Hive | Digital & Creative Solutions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary: #6a11cb;
            --secondary: #2575fc;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --accent: #ff6b6b;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }
        
        /* Header & Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 100px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
            opacity: 0.3;
        }
        
        .hero h1 {
            font-size: 4rem;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            position: relative;
            animation: fadeInDown 1s ease both;
        }
        
        .hero p {
            font-size: 1.4rem;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
            animation: fadeIn 1s 0.3s ease both;
            position: relative;
        }
        
        .cta-button {
            display: inline-block;
            background: white;
            color: var(--primary);
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            animation: fadeInUp 1s 0.6s ease both;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .cta-button:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        .cta-button:hover::before {
            opacity: 1;
        }
        
        /* Floating elements animation */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 0;
        }
        
        .floating-element {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
            100% {
                transform: translateY(0) rotate(360deg);
            }
        }
        
        /* Main Sections */
        .section {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
        }
        
        .section h2 {
            text-align: center;
            margin-bottom: 50px;
            font-size: 2.5rem;
            color: var(--dark);
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .section h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }
        
        /* Services Grid */
        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .service {
            background: #fff;
            padding: 40px 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .service::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: height 0.3s ease;
            z-index: -1;
        }
        
        .service:hover::before {
            height: 100%;
        }
        
        .service i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .service:hover i {
            color: white;
            transform: scale(1.1);
        }
        
        .service h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .service p {
            color: #666;
            transition: all 0.3s ease;
        }
        
        .service:hover h3,
        .service:hover p {
            color: white;
        }
        
        .service:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        /* Features Section */
        .features {
            background: linear-gradient(135deg, #f5f7fa, #e4e8f0);
            padding: 60px;
            border-radius: 15px;
            margin-top: 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .features::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0) 70%);
            animation: pulse 8s infinite linear;
            z-index: 0;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.3;
            }
            100% {
                transform: scale(0.8);
                opacity: 0.5;
            }
        }
        
        .features h2 {
            position: relative;
            z-index: 1;
        }
        
        .features p {
            font-size: 1.2rem;
            line-height: 1.8;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        /* Stats Counter */
        .stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 50px;
            gap: 20px;
        }
        
        .stat-item {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            min-width: 200px;
            transition: all 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: #666;
        }
        
        /* Contact Section */
        .contact {
            text-align: center;
            margin-top: 80px;
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .contact::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            clip-path: polygon(0 0, 100% 0, 100% 100%);
            opacity: 0.1;
        }
        
        .contact h2 {
            margin-bottom: 30px;
        }
        
        .contact-info {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 30px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            background: rgba(106, 17, 203, 0.05);
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .contact-item:hover {
            background: rgba(106, 17, 203, 0.1);
            transform: translateY(-3px);
        }
        
        .contact-item i {
            font-size: 1.5rem;
            margin-right: 15px;
            color: var(--primary);
        }
        
        .contact-item a {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .contact-item:hover a {
            color: var(--primary);
        }
        
        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            text-align: center;
            padding: 40px 20px;
            margin-top: 100px;
            position: relative;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .social-links {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transform: translateY(-3px);
        }
        
        .social-link i {
            color: white;
            font-size: 1.2rem;
        }
        
        .copyright {
            margin-top: 20px;
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        /* Scroll animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .section h2 {
                font-size: 2rem;
            }
            
            .services {
                grid-template-columns: 1fr;
            }
            
            .stats {
                flex-direction: column;
                align-items: center;
            }
            
            .stat-item {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>

    <div class="hero">
        <div class="floating-elements">
            <div class="floating-element" style="width: 50px; height: 50px; top: 20%; left: 10%;"></div>
            <div class="floating-element" style="width: 30px; height: 30px; top: 70%; left: 80%;"></div>
            <div class="floating-element" style="width: 40px; height: 40px; top: 40%; left: 60%;"></div>
            <div class="floating-element" style="width: 60px; height: 60px; top: 80%; left: 20%;"></div>
        </div>
        <h1>Creative Hive</h1>
        <p>Your one-stop destination for websites, marketing, design, and media — delivered quickly, creatively, and affordably.</p>
        <a href="#contact" class="cta-button">Get Started</a>
    </div>

    <div class="section reveal">
        <h2>Our Services</h2>
        <div class="services">
            <div class="service">
                <i class="fas fa-globe"></i>
                <h3>Static Websites</h3>
                <p>Beautiful, fast-loading websites perfect for your online presence</p>
            </div>
            <div class="service">
                <i class="fas fa-code"></i>
                <h3>Dynamic Web Apps</h3>
                <p>Interactive web applications tailored to your business needs</p>
            </div>
            <div class="service">
                <i class="fas fa-bullhorn"></i>
                <h3>Digital Marketing</h3>
                <p>Boost your online visibility and reach your target audience</p>
            </div>
            <div class="service">
                <i class="fas fa-palette"></i>
                <h3>Graphic Design</h3>
                <p>Stunning visuals that communicate your brand identity</p>
            </div>
            <div class="service">
                <i class="fas fa-video"></i>
                <h3>Video Editing</h3>
                <p>Professional editing to make your videos stand out</p>
            </div>
            <div class="service">
                <i class="fas fa-camera"></i>
                <h3>Event Photography</h3>
                <p>Capture your special moments with our expert photographers</p>
            </div>
            <div class="service">
                <i class="fas fa-film"></i>
                <h3>Event Videography</h3>
                <p>High-quality video coverage for your important events</p>
            </div>
            <div class="service">
                <i class="fas fa-mobile-alt"></i>
                <h3>App Development</h3>
                <p>Custom mobile applications for iOS and Android</p>
            </div>
        </div>
    </div>

    <div class="section features reveal">
        <h2>Why Choose Us?</h2>
        <p>
            A team of dedicated professionals offering fast, affordable, and creative digital solutions.
            We specialize in high-quality work with fast delivery — perfect for startups, events, and small businesses.
            Our approach combines creativity with technical excellence to deliver results that exceed expectations.
        </p>
        
        <!-- <div class="stats">
            <div class="stat-item">
                <div class="stat-number" data-count="150">0</div>
                <div class="stat-label">Projects Completed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="98">0</div>
                <div class="stat-label">Client Satisfaction</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="50">0</div>
                <div class="stat-label">Creative Minds</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="24">0</div>
                <div class="stat-label">Hour Support</div>
            </div>
        </div>
    </div> -->

    <div class="section contact reveal" id="contact">
        <h2>Get In Touch</h2>
        <p>Ready to start your project? Contact us today for a free consultation.</p>
        
        <div class="contact-info">
            <div class="contact-item">
                <i class="fas fa-phone-alt"></i>
                <a href="tel:+916383562660">+91 63835 62660</a>
            </div>
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <a href="mailto:sasikumar80738@gmail.com">sasikumar80738@gmail.com</a>
            </div>
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <a href="#">Chennai, India</a>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <h3>Creative Hive</h3>
            <p>Digital & Creative Solutions for the Modern World</p>
            
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-link"><i class="fab fa-behance"></i></a>
            </div>
            
            <div class="copyright">
                &copy; <span id="year"></span> Creative Hive. All rights reserved.
            </div>
        </div>
    </div>

    <script>
        // Set current year in footer
        document.getElementById('year').textContent = new Date().getFullYear();
        
        // Scroll reveal effect
        const reveals = document.querySelectorAll('.reveal');
        window.addEventListener('scroll', () => {
            for (let el of reveals) {
                const windowHeight = window.innerHeight;
                const elementTop = el.getBoundingClientRect().top;
                const elementVisible = 150;
                if (elementTop < windowHeight - elementVisible) {
                    el.classList.add('active');
                }
            }
        });
        
        // Trigger animations on load
        window.addEventListener('load', () => {
            document.querySelector('.hero h1').classList.add('animate__animated', 'animate__fadeInDown');
            document.querySelector('.hero p').classList.add('animate__animated', 'animate__fadeIn');
            document.querySelector('.cta-button').classList.add('animate__animated', 'animate__fadeInUp');
        });
        
        // Animated counter for stats
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200;
            
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;
                const increment = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(animateCounters, 1);
                } else {
                    counter.innerText = target;
                }
            });
        }
        
        // Start counter animation when stats section is visible
        const statsSection = document.querySelector('.features');
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                animateCounters();
                observer.unobserve(statsSection);
            }
        });
        
        observer.observe(statsSection);
        
        // Floating elements animation
        const floatingElements = document.querySelectorAll('.floating-element');
        floatingElements.forEach((el, index) => {
            el.style.animationDelay = `${index * 2}s`;
        });
    </script>
</body>
</html>