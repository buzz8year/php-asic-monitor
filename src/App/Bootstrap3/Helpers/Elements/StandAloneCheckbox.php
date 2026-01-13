<?php
namespace App\Bootstrap3\Helpers\Elements;

class StandAloneCheckbox extends Checkbox
{
    public function out()
    {
        ?>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <div class="checkbox">
                    <label>
                        <input
                            type="checkbox"
                            id="<?php print $this->getId()?>"
                            name="<?php print $this->getName()?>"
                            value="<?php print $this->getValue()?>"
                            <?php if ($this->isChecked()) print " checked";?> /> <?php print $this->getTitle()?>
                    </label>

                    <?php if ($this->getDescription()):?>
                        <div class="text-muted"><?php print $this->getDescription()?></div>
                    <?php endif;?>
                </div>
            </div>
        </div>

        <?php
    }

}
