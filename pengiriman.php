<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Pengiriman</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f5f7fa;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .circle-container {
            position: relative;
            width: 250px;
            height: 250px;
            margin-bottom: 30px;
        }

        .rotating-ring {
            position: absolute;
            top: 0;
            left: 0;
            width: 250px;
            height: 250px;
            border: 6px solid #007bff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 3s linear infinite;
        }

        .center-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .center-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .btn-home:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 15px;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
            user-select: none;
        }
    </style>
</head>

<body>

    <div class="circle-container">
        <div class="rotating-ring"></div>
        <div class="center-image">
            <img src="your-image.png" alt="Gambar Pengiriman" />
        </div>
    </div>

    <a href="index.php" class="btn-secondary">← Kembali ke Homepage</a>


</body>

</html>