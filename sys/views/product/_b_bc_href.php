<a href="<?= Url::to(['brand/view', 'id' => $brand->getId()->getId(), 'ufu' => $brand->getUfu()->getUfu()]); ?>" class="text-secondary">
                    <?= Html::encode($brand->getName()->getName()) ?>
                </a>
                <?php if (!is_null($brandCategory)) : ?>
                    /
                    <a href="<?= Url::to(['brandcategory/view', 'id' => $brandCategory->getId()->getId(), 'ufu' => $brandCategory->getUfu()->getUfu(),]); ?>" class="text-secondary">
                        <?= Html::encode($brandCategory->getName()->getName()) ?>
                    </a>
                <?php endif; ?>