<?php
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\ViewInterface;

class SelectDate extends Text implements ViewInterface
{
	/**
	 * Render output
	 */
    public function out()
    {
        ?>
    
    	        <div class="form-group">
    				<label class="control-label col-sm-3" for="<?php print $this->getId()?>"><?php print $this->getTitle()?></label>
    				<div class="col-sm-9">
    					<div class="input-group input-group-in date" data-input="datepicker" data-date="<?php print $this->getValue()?>" data-format="mm/dd/yyyy">
    	                	<input
								name="<?php print $this->getName()?>"
								id="<?php print $this->getId()?>"
								class="form-control"
								type="text"
								placeholder="<?php print $this->placeholder;?>"
								value="<?php print $this->getValue();?>">
    	                    <span class="input-group-addon text-silver"><i class="fa fa-calendar"></i></span>
    					</div>
    				</div>
    			</div>
            
            <?php         
	}

	// Unknown how the previous calendar works
    public function out2()
	{
		?>
				<div class="form-group">
					<label class="control-label col-sm-3" for="<?php print $this->getId()?>"><?php print $this->getTitle()?></label>
					<div class="col-sm-9">
						<input class="form-control app-date"
							   id="<?php print $this->getId()?>"
							   name="<?php print $this->getName()?>"
							   placeholder="<?php print $this->placeholder;?>"
							   value="<?php print $this->getValue();?>">
					</div>
				</div>
		<?php
	}
}