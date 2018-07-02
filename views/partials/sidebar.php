<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>

<div class="col-md-4 data-sticky_column">
    <div class="primary-sidebar">

        <aside class="widget">
            <h3 class="widget-title text-uppercase text-center">Популярные посты</h3>
        <?php foreach ($popular as $article): ?>
            <div class="popular-post">

                <a href="#" class="popular-img"><img src="<?= $article->getImage(); ?>" alt="">

                    <div class="p-overlay"></div>
                </a>

                <div class="p-content">
                    <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id])?>" class="text-uppercase"><?= $article->title; ?></a>
                    <span class="p-getDate()"><?= $article->getDate(); ?></span>

                </div>
            </div>
        <?php endforeach; ?>
        </aside>


        <aside class="widget pos-padding">
            <h3 class="widget-title text-uppercase text-center">Новые посты</h3>
            <div class="thumb-latest-posts">
                <div class="media">
            <?php foreach ($recent as $article): ?>
                    <div class="media-left">
                        <a href="#" class="popular-img"><img src="<?= $article->getImage(); ?>" alt="">
                            <div class="p-overlay"></div>
                        </a>
                    </div>
                    <div class="p-content">
                        <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id])?>" class="text-uppercase"><?= $article->title; ?></a>
                        <span class="p-getDate()"><?= $article->getDate(); ?></span>
                    </div>

            <?php endforeach; ?>
            </div>
            </div>
        </aside>


          <aside class="widget border pos-padding">
                                 <h3 class="widget-title text-uppercase text-center">Категории</h3>
                     <ul>
                         <?php foreach($categories as $category):?>
                             <li>
                                 <a href="<?= Url::toRoute(['site/category','id'=>$category->id]);?>"><?= $category->title?></a>
                                 <span class="post-count pull-right"> (<?= $category->getArticlesCount();?>)</span>
                             </li>
                         <?php endforeach;?>
                     </ul>
         </aside>
    </div>
</div>
