    <header>
        <div class="heading">
            <div class="left-heading">
                <div class="logo">
                    <a href="/GroceryWebsite/index.php"><img src="/GroceryWebsite/images/logo.png"/></a>
                </div>
                <div class="title">
                    <a href="/GroceryWebsite/index.php">Sunny Mart</a>
                </div>
            </div>
            <div class="right-heading">
                <ul class="top-nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <div class="search">
                <form method="get" class="searchBox" action="search.php">
                    <input type="text" placeholder="Search products" name="search_query">
                    <input type="number" placeholder="Min Price" name="min_price" min= "0" step="0">
                    <input type="number" placeholder="Max Price" name="max_price" min="0" step="1">
                    <button type="submit" class="searchBtn"><ion-icon name="search-outline"></ion-icon></button>
                </form>
                </div>
            </div>
        </div>
    </header>