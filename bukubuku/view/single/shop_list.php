<?php
require_once('./header.php')
?>
<article class="shoplist">
    <section class="title">
        <h2><span class="en">SHOP LIST</span>店舗一覧</h2>
    </section>
    <div class="container">
        <?php
            $page_name = 'SHOP LIST'; 
            require_once('./breadcrumb.php');
        ?>
        <div class="d-flex justify-content-center">
            <main class="w-1000">
                <div class="search">
                    <h3>検索結果一覧</h3>
                    <div class="search_detail">
                        <div>
                            <dl class="features">
                                <dt>特徴</dt>
                                <dd></dd>
                            </dl>
                            <dl class="area">
                                <dt>カテゴリのエリア</dt>
                                <dd>小エリア</dd>
                            </dl>
                        </div>
                        <select name="" id="">
                            <option value="">新着順</option>
                        </select>
                    </div>
                </div>
                <ul class="list">
                    <?php for($i = 0;$i < 5;$i++):?>
                    <li>
                        <p class="name">店舗名店舗名店舗名店舗名</p>
                        <div class="img">
                            <figure><img src="./src/img/sample/sample_shop.jpg" alt=""></figure>
                        </div>
                        <div class="info">
                            <ul>
                                <li>
                                    <dl class="area">
                                        <dt>カテゴリのエリア</dt>
                                        <dd>小エリア</dd>
                                    </dl>
                                    <dl class="features">
                                        <dt>特徴</dt>
                                        <dd></dd>
                                    </dl>
                                </li>
                                <li class="address"><i class="fas fa-map-marker-alt"></i>東京都◯◯区◯◯町◯◯1-1-1　サンプルビル1F</li>
                                <li class="time">
                                    <dl>
                                        <dt>営業時間</dt>
                                        <dd>16時～23時　LO:22時30分</dd>
                                    </dl>
                                </li>
                            </ul>
                        </div>
                        <div class="shop_tags">
                            <ul>
                                <li class="active">Wi-Fi使用可</li>
                                <li>30席以上あり</li>
                                <li>飲食持ち込み可</li>
                                <li class="active">駐車場あり</li>
                                <li>電源あり</li>
                                <li class="active">予約可</li>
                                <li>喫煙可</li>
                                <li>貸し切り可</li>
                            </ul>
                        </div>
                        <p class="to_detail"><a href="./shops.php">店舗詳細を見る</a></p>
                    </li>
                    <?php endfor;?>
                </ul>
                <ul class="pagenation">
                    <li class="active"><a href="">1</a></li>
                    <li><a href="">2</a></li>
                    <li><a href="">3</a></li>
                    <li><a href="">4</a></li>
                    <li><a href="">…</a></li>
                    <li><a href="" class="kana">次のページ</a></li>
                </ul>
            </main>
            <aside>
                <section>
                    <h3>KEYWORD</h3>
                    <form action=""><input type="text"><button><i class="fas fa-search"></i></button></form>
                </section>
                <section class="link">
                    <h3>エリア</h3>
                    <nav>
                        <ul>
                            <li>
                                <a href="">エリアエリアエリアエリア</a>
                                <ul>
                                    <li><a href="">小エリア小エリア</a></li>
                                    <li><a href="">小エリア小エリア</a></li>
                                    <li><a href="">小エリア小エリア</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="">エリアエリアエリアエリア</a>
                                <ul>
                                    <li><a href="">小エリア小エリア</a></li>
                                    <li><a href="">小エリア小エリア</a></li>
                                    <li><a href="">小エリア小エリア</a></li>
                                </ul>
                            </li>
                            <li><a href="">エリアエリアエリアエリア</a></li>
                            <li><a href="">エリアエリアエリアエリア</a></li>
                            <li><a href="">エリアエリアエリアエリア</a></li>
                        </ul>
                    </nav>
                </section>
                <section class="link">
                    <h3>特徴</h3>
                    <nav>
                        <ul>
                            <li><a href="">特徴テキストテキストテキスト</a></li>
                            <li><a href="">特徴テキストテキストテキスト</a></li>
                            <li><a href="">特徴テキストテキストテキスト</a></li>
                            <li><a href="">特徴テキストテキストテキスト</a></li>
                            <li><a href="">特徴テキストテキストテキスト</a></li>
                            <li><a href="">特徴テキストテキストテキスト</a></li>
                        </ul>
                    </nav>
                </section>
            </aside>
        </div>
    </div>
</article>


<?php require_once('./footer.php')?>