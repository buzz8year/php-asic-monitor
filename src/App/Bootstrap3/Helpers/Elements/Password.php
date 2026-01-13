<?php
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\ViewInterface;

class Password extends Text implements ViewInterface
{
    public function out()
    {
        ?>
            	<div class="form-group">
    				<label class="control-label col-sm-3" for="<?php print $this->getId()?>"><?php print $this->getTitle()?><?php if ($this->require) print '<span class="text-danger">&nbsp;*</span>';?></label>
    				<div class="col-sm-9">
    					<input
                            id="<?php print $this->getId()?>"
                            class="form-control"
                            type="password"
                            autocomplete="new-password"
                            name="<?php print $this->getName();?>"
                            placeholder="<?php print $this->getPlaceholder();?>"
                            maxlength="<?php print $this->getMaxLength()?>"
                            value="<?php print $this->getValue();?>"
                            <?php print $this->getDisabled() ? " disabled" : null?>>
    				</div>
    			</div>    			
            <?php         
        }
}