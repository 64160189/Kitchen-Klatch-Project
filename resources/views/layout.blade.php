<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen klatch | @yield('title')</title>

    <style>
        header {
            font-family: Arial, sans-serif;
            background-color: #EEEEEE;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            display: flex;
            align-items: center;
            z-index: 1000;
        }

        .kk-icon {
            color: #F21D2F;
            padding-left: 20px;
        }

        .search {
            display: flex;
            flex-direction: row;
        }

        .searchbox {
            background-color: #ffffff;
            height: 35px;
            width: 500px;
            border-radius: 25px;
        }

        .searchbar {
            margin-left: 20px;
            border: none;
            height: 95%;
            width: 460px;
            font-size: 16px;
        }

        .searchbar:focus {
            border: none;
            outline: none;
        }

        .search-btn {
            margin-left: 5px;
            background-color: #F21D2F;
            color: #ffffff;
            height: 35px;
            width: 35px;
            border-radius: 35px;
            font-size: 18px;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .search-btn:hover {
            cursor: pointer;
            background-color: #bf1725;
        }

        .login-btn {
            background-color: #F21D2F;
            color: #ffffff;
            height: 35px;
            width: 90px;
            border-radius: 10px;
            font-size: 18px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border: none;
        }

        .login-btn:hover {
            cursor: pointer;
            background-color: #bf1725;
        }
    </style>
</head>

<body>

    <header>
        <h1 class="kk-icon">Kitchen klatch</h1>
        <div class="search">
            <div class="searchbox">
                <input type="text" class="searchbar" placeholder="ค้นหาสูตรอาหาร">
            </div>
            <button class="search-btn">s</button>
        </div>
        <div>
            <a href="/login"><button class="login-btn">LOG IN</button></a>
            <a href="/posting"><button style="margin-left: 10px;">Post</button></a>
        </div>
    </header>

</body>

</html>
