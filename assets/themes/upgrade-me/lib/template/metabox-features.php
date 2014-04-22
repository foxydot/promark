<?php global $wpalchemy_media_access; ?>
<?php 

$postid = is_admin()?$_GET['post']:$post->ID;
$template_file = get_post_meta($postid,'_wp_page_template',TRUE);
  // check for a template type
if (is_admin() && $template_file == 'front-page.php') { ?>
<style>
    .homepage_meta_control .table {display: block; width: 100%;}
    .homepage_meta_control .row {display: block;cursor: move;border-bottom: 1px solid #333;}
    .homepage_meta_control .row:before,
.homepage_meta_control .row:after {
    content: " "; /* 1 */
    display: table; /* 2 */
}

.homepage_meta_control .row:after {
    clear: both;
}

/**
 * For IE 6/7 only
 * Include this rule to trigger hasLayout and contain floats.
 */
.homepage_meta_control .row {
    *zoom: 1;
}
.homepage_meta_control .cell {display: block; clear: both;margin-left: 1rem;}
    .even {background: #eee;}
    .odd {background: #fff;}
    .file input[type="text"] {width: 75%}
    .homepage_meta_control label{ display:block; font-weight:bold; margin-right: 1%;float: left; width: 14%; text-align: right;}
 .input_container{width: 85%;float: left;}
.homepage_meta_control textarea, .homepage_meta_control input[type='text'], .homepage_meta_control select,.homepage_meta_control .wp-editor-wrap
{ display:inline;margin-bottom:3px; width: 90%;
     }
     .homepage_meta_control .file input[type='text']{width: 76%;}
</style>
<div class="homepage_meta_control">
 <p id="warning" style="display: none;background:lightYellow;border:1px solid #E6DB55;padding:5px;">Order has changed. Please click Save or Update to preserve order.</p>
    <div class="table">
    <?php $i = 0; ?>
    <?php while($mb->have_fields('features',4)): ?>
    <?php $mb->the_group_open(); ?>
    <div class="row <?php print $i%2==0?'even':'odd'; ?>">
        <div class="cell">
        <?php $mb->the_field('title'); ?>
        <label>Feature Area Title</label>            
        <div class="input_container">
            <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></div>
        </div>
        <div class="cell">
        <?php $mb->the_field('url'); ?>
        <label>Feature Area URL</label>            
        <div class="input_container">
            <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></div>
        </div>
        <div class="cell file">
            <label>Grayscale Image</label>
            <div class="input_container">
        <?php $mb->the_field('bw_image'); ?>
        <?php $wpalchemy_media_access->setGroupName('bw-img'. $mb->get_the_index())->setInsertButtonLabel('Insert This')->setTab('gallery'); ?>
        <?php echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value())); ?>
        <?php echo $wpalchemy_media_access->getButton(array('label' => 'Add Image')); ?>
            </div>
        </div>        
        <div class="cell file">
            <label>Color Image</label>
            <div class="input_container">
        <?php $mb->the_field('color_image'); ?>
        <?php $wpalchemy_media_access->setGroupName('color-img'. $mb->get_the_index())->setInsertButtonLabel('Insert This')->setTab('gallery'); ?>
        <?php echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value())); ?>
        <?php echo $wpalchemy_media_access->getButton(array('label' => 'Add Image')); ?>
            </div>
        </div>
    </div>
    <?php $i++; ?>
    <?php $mb->the_group_close(); ?>
    <?php endwhile; ?>
    </div>
</div>
<script>
jQuery(function($){
    $("#wpa_loop-tabs").sortable({
        change: function(){
            $("#warning").show();
        }
    });
});</script>
<?php } else {
    print "Select \"Front Page\" template and save to activate.";
} ?>
