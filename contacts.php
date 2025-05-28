<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agencia Elmas Capitos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                background-color: #f8f9fa;
            }
            .form-container {
                max-width: 1000px;
                margin: 15px auto;
                padding: 25px;
                border: 1px solid #ddd;
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                background-color: #ffffff;
            }
            .contact-title {
                color: #333;
                margin-bottom: 10px;
                text-align: center;
                font-size: 2rem;
                font-weight: 600;
            }
            .contact-subtitle {
                color: #666;
                text-align: center;
                margin-bottom: 20px;
                font-size: 1rem;
            }
            .contact-section {
                margin-bottom: 20px;
                padding: 15px;
                background-color: #f8f9fa;
                border-radius: 10px;
            }
            .contact-section h3 {
                color: #333;
                margin-bottom: 10px;
                font-size: 1.3rem;
            }
            .contact-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }
            .map-card {
                background: white;
                padding: 10px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                height: 100%;
                display: flex;
                flex-direction: column;
            }
            .map-card h4 {
                color: #333;
                margin-bottom: 8px;
                font-size: 1rem;
                text-align: center;
            }
            .map-container {
                flex-grow: 1;
                border-radius: 8px;
                overflow: hidden;
            }
            .map-container iframe {
                width: 100%;
                height: 100%;
                border: none;
            }
            .contact-details {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .info-details-stack {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .info-card {
                background: white;
                padding: 10px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                text-align: center;
            }
            .info-card i {
                font-size: 1.6rem;
                color: #007bff;
                margin-bottom: 8px;
            }
            .info-card h4 {
                color: #333;
                margin-bottom: 5px;
                font-size: 1rem;
            }
            .info-card p {
                color: #666;
                margin: 0;
            }
            .contact-buttons-card h4 {
                color: #333;
                margin-bottom: 8px;
                font-size: 1rem;
                text-align: center;
            }
            .contact-buttons {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            .contact-button {
                display: flex;
                align-items: center;
                gap: 6px;
                padding: 8px 12px;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.3s ease;
                text-decoration: none;
                color: white;
                border: none;
                justify-content: center;
                font-size: 0.9rem;
            }
            .contact-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                color: white;
            }
            .contact-button i {
                font-size: 1.2rem;
            }
            .email-button {
                background-color: #007bff;
            }
            .email-button:hover {
                background-color: #0056b3;
            }
            .whatsapp-button {
                background-color: #25D366;
            }
            .whatsapp-button:hover {
                background-color: #128C7E;
            }
            .facebook-button {
                background-color: #1877F2;
            }
            .facebook-button:hover {
                background-color: #0d6efd;
            }
            .instagram-button {
                background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            }
            .instagram-button:hover {
                background: linear-gradient(45deg, #e68a33 0%, #d65c3c 25%, #c22743 50%, #b22366 75%, #a21888 100%);
            }
            .admin-login {
                position: fixed;
                bottom: 20px;
                left: 20px;
                font-size: 0.9rem;
                color: #6c757d;
                text-decoration: none;
                padding: 8px 15px;
                border-radius: 5px;
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                transition: all 0.3s ease;
            }
            .admin-login:hover {
                color: #343a40;
                background-color: #e9ecef;
            }
            .business-hours {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            .business-hours h4 {
                color: #333;
                margin-bottom: 15px;
            }
            .hours-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .hours-list li {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                border-bottom: 1px solid #eee;
            }
            .hours-list li:last-child {
                border-bottom: none;
            }
            .phone-link {
                color: #666;
                text-decoration: none;
                transition: color 0.3s ease;
            }
            .phone-link:hover {
                color: #007bff;
            }
            @media (max-width: 992px) {
                .contact-grid {
                    grid-template-columns: 1fr;
                }
                .map-container {
                    height: 200px;
                }
                .map-card {
                    height: auto;
                }
                .contact-details {
                    grid-template-columns: 1fr;
                    gap: 15px;
                }
            }
            @media (max-width: 768px) {
                .form-container {
                    margin: 10px;
                    padding: 10px;
                }
                .contact-button {
                    width: 100%;
                }
                .contact-buttons {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <?php include 'user_navbar.php'; ?>
        
        <div class="container mt-4">
            <div class="form-container">
                <h1 class="contact-title">Contáctanos</h1>
                <p class="contact-subtitle">Estamos aquí para ayudarte. Elige tu método preferido de contacto.</p>
                
                <div class="contact-section">
                    <h3>Información de Contacto</h3>
                    <div class="contact-grid">
                        <div class="map-card">
                            <h4>Ubicación</h4>
                            <div class="map-container">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d221701.64881675006!2d-95.32935619354242!3d29.746158656884745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2smx!4v1748386728633!5m2!1sen!2smx" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                        <div class="contact-details">
                            <div class="info-card">
                                <i class="fas fa-phone"></i>
                                <h4>Teléfono</h4>
                                <p>
                                    <a href="tel:+17135551234" class="phone-link">+1 (713) 555-1234</a><br>
                                    <a href="tel:+17135551235" class="phone-link">+1 (713) 555-1235</a>
                                </p>
                            </div>
                            <div class="info-card">
                                <i class="fas fa-clock"></i>
                                <h4>Horario</h4>
                                <p>Lunes a Viernes: 9AM - 6PM<br>Sábados: 9AM - 1PM</p>
                            </div>
                            <div class="info-card contact-buttons-card">
                                <h4>Métodos de Contacto</h4>
                                <div class="contact-buttons">
                                    <a href="mailto:loulou@gmail.com" class="contact-button email-button">
                                        <i class="fas fa-envelope"></i>
                                        Solicitar Informes
                                    </a>
                                    <a href="https://wa.me/17135551234" class="contact-button whatsapp-button">
                                        <i class="fab fa-whatsapp"></i>
                                        WhatsApp
                                    </a>
                                    <a href="https://www.facebook.com/loulou.loulou.5" class="contact-button facebook-button">
                                        <i class="fab fa-facebook-f"></i>
                                        Facebook
                                    </a>
                                    <a href="https://www.instagram.com/loulou.loulou.5" class="contact-button instagram-button">
                                        <i class="fab fa-instagram"></i>
                                        Instagram
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="login.php" class="admin-login">Admin Login</a>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>