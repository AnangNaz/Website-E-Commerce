<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Pengiriman</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        .circle-container {
            position: relative;
            width: 250px;
            height: 250px;
            margin-bottom: 30px;
            animation: fadeIn 1s ease-in;
        }

        .rotating-ring {
            position: absolute;
            top: 0;
            left: 0;
            width: 250px;
            height: 250px;
            border: 6px solid  #4300FF;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 3s linear infinite;
        }

        .center-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 110px;
            height: 110px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            background: #fff;
        }

        .center-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-secondary {
            background-color:  #4300FF;
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .btn-secondary:hover {
            background-color: #0118D8;
            box-shadow: 0 6px 18px rgba(0, 86, 179, 0.4);
            transform: translateY(-2px);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="circle-container">
        <div class="rotating-ring"></div>
        <div class="center-image">
            <img src="assets/OIP.webp" alt="Gambar Pengiriman" />
        </div>
    </div>

    <a href="index.php" class="btn-secondary">← Kembali ke Homepage</a>

</body>

</html>