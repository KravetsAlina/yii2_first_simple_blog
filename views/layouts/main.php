<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\PublicAsset;
use app\assets\AppcAsset;
use yii\helpers\Url;

PublicAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>

<nav class="navbar main-menu navbar-default">
    <div class="container">
        <div class="menu-content">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="/public/images/if_bloglovin.png" alt=""></a>
            </div>


            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav text-uppercase">
                    <li><a data-toggle="dropdown" class="dropdown-toggle" href="/">Home</a>

                    </li>
                </ul>
                <div class="i_con">
                  <ul class="nav navbar-nav text-uppercase">
                           <?php if(Yii::$app->user->isGuest): ?>
                               <li><a href="<?= Url::toRoute(['auth/login'])?>">Login</a></li>
                               <li><a href="<?= Url::toRoute(['auth/signup'])?>">Register</a></li>
                          <?php elseif(Yii::$app->user->identity->isAdmin): ?>
                                <li><a href="<?= Url::toRoute(['/admin/article'])?>">Admin</a></li>
                                <li><div id="btn-logout"><?= Html::beginForm(['/auth/logout'], 'post'). Html::submitButton(
                                       'Logout (' . Yii::$app->user->identity->name . ')',
                                       ['class' => 'btn btn-link logout', 'style'=>"padding-top:10px;"]). Html::endForm() ?></div><li>
                           <?php else: ?>
                               <?= Html::beginForm(['/auth/logout'], 'post')
                               . Html::submitButton(
                                   'Logout (' . Yii::$app->user->identity->name . ')',
                                   ['class' => 'btn btn-link logout', 'style'=>"padding-top:10px;"]
                               )
                               . Html::endForm() ?>
                           <?php endif;?>
                  </ul>
                </div>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </div>
    <!-- /.container-fluid -->
</nav>

<?=$content ?>

<!--footer start-->


<footer class="footer-widget-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <aside class="footer-widget">
                    <div class="address">
                        <h4 class="text-uppercase">contact info</h4>

                        <p> Ukraine, Kiew, Some Street</p>

                        <p> Phone: +123 456 789</p>

                        <p>alina_K.com</p>
                    </div>
                </aside>
            </div>
            <div class="col-md-6">
                <aside class="footer-widget">
                  <div class="address">
                      <h4 class="text-uppercase">follow us...</h4>
                      <div id="face1">
                        <a href="#">
                          <img src="/public/images/facebook.png">
                        </a>
                      </div>
                      <div id="face2">
                        <a href="#">
                          <img src="/public/images/google.png">
                        </a>
                      </div>
                      <div id="face3">
                        <a href="#">
                          <img src="/public/images/instagram.png">
                        </a>
                      </div>
                      <div id="face4">
                        <a href="#">
                          <img src="/public/images/twitter.png">
                        </a>
                      </div>


                  <!-- </div> -->
                </aside>
            </div>
        </div>
    </div>
    <div class="footer-copy">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">&copy; <?= date('Y') ?> <a href="#">Alina K. </a>                     </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
