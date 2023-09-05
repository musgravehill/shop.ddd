<?php

use yii\helpers\Html;
use app\components\HelperY;
use yii\helpers\Url;
use app\models\ProductHelper;
use app\models\SaleHelper;
?>

<?php if ($p->photo_url_1): ?>
    <?php $uuid = HelperY::GUID(false); ?>
    <div class="col-12 col-md-6 mb-3">
        <div class="site__carousel">
            <div class="site__carousel_img_container" data-uuid="<?= $uuid ?>">
                <?php for ($i = 1; $i < 5; $i++): ?>
                    <?php if ($p->{'photo_url_' . $i}): ?>
                        <img data-uuid="<?= $uuid ?>" data-id="<?= $i ?>" src="<?= Html::encode($p->{'photo_url_' . $i}) ?>" class="site__carousel_img" />
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <div class="site__carousel_icon_container">
                <?php for ($i = 1; $i < 5; $i++): ?>
                    <?php if ($p->{'photo_url_' . $i}): ?>
                        <img data-uuid="<?= $uuid ?>" data-id="<?= $i ?>" src="<?= Html::encode($p->{'photo_url_' . $i}) ?>" class="site__carousel_icon" />
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>  
        <div class="modal fade" id="site__carousel_modal_<?= $uuid ?>" tabindex="-1" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-0">                                             
                        <button type="button" class="close helper-font-30" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="site__carousel_modal_carousel_<?= $uuid ?>" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <?php $isFirst = true; ?>
                                <?php for ($i = 1; $i < 5; $i++): ?>
                                    <?php if ($p->{'photo_url_' . $i}): ?>
                                        <div class="carousel-item <?= $isFirst ? 'active' : '' ?> ">
                                            <img src="<?= Html::encode($p->{'photo_url_' . $i}) ?>" class="d-block w-100">
                                        </div>     
                                        <?php $isFirst = false; ?>                                                            
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <a class="carousel-control-prev" href="#site__carousel_modal_carousel_<?= $uuid ?>" role="button" data-slide="prev">
                                <span class="icon-chevron-left text-dark" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#site__carousel_modal_carousel_<?= $uuid ?>" role="button" data-slide="next">
                                <span class="icon-chevron-right text-dark" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>