<div id="find-posts" class="find-box" style="display:none;">
  <div id="find-posts-head" class="find-box-head">Find Posts or Pages</div>
  <div class="find-box-inside">
    <div class="find-box-search">
      <input type="hidden" name="affected" id="affected" value="" />
      <?php wp_nonce_field( 'find-posts', '_ajax_nonce', false ); ?>
      <label class="screen-reader-text" for="find-posts-input">Search</label>
      <input type="text" id="find-posts-input" name="ps" value="" />
      <input type="button" onclick="findPosts.send();" value="Search" class="button" />
      <br />
      <input type="radio" name="find-posts-what" id="find-posts-post" value="post"  checked='checked' />
      <label for="find-posts-post">Posts</label>
      <input type="radio" name="find-posts-what" id="find-posts-page" value="page"  />
      <label for="find-posts-page">Pages</label>
    </div>
    <div id="find-posts-response"></div>
  </div>
  <div class="find-box-buttons">
    <input type="button" class="button alignleft" onclick="findPosts.close();" value="Close" />
    <input id="find-posts-submit" name="submit" type="submit" class="button-primary alignright" value="Select" />
  </div>
</div>
<fieldset>
  <legend>Main Settings</legend>
  <ul class="thethe-settings-list">
    <li>
      <label>Activate:</label>
      <select name="is_active" class="text-field">
        <option value="0"<?php echo ($options['is_active'] == '0') ? ' selected="selected"': "" ;?>>Usage with shortcodes only</option>
        <option value="1"<?php echo ($options['is_active'] == '1') ? ' selected="selected"': "" ;?>>Globaly throughout the site and allow shortcode overwrites</option>
      </select>
      <a class="tooltip" href="#">?<span>Select the usage type for this plugin.</span></a> </li>
    <li>
      <label>Redirect Type:</label>
      <select name="type" class="text-field">
        <option value="url"<?php echo ($options['redirect_type'] == 'url') ? ' selected="selected"': "" ;?>>To URL</option>
        <option value="post"<?php echo ($options['redirect_type'] == 'post') ? ' selected="selected"': "" ;?>>To Page or post ID</option>
      </select>
      <a class="tooltip" href="#">?<span>Select the redirect type for this plugin.</span></a> </li>
    <li>
      <label>URL or ID: (<a href="#" onclick="findPosts.open();return false;">Find</a>)</label>
      
      <input  type="text" class="text-field" name="value" value="<?php echo $options['redirect_value'];?>" />
      <a class="tooltip" href="#">?<span>Enter URL or post (page) ID for redirect.<br />(URL enter with "http://"<br />e.g. "http://anywhere.com")</span></a> </li>
    <li>
      <label>Window Text:</label>
      <textarea class="text-field" rows="10" name="body"><?php echo $options['redirect_body'];?></textarea>
      <a class="tooltip" href="#">?<span>Enter the text for the modal window.</span></a> </li>
  </ul>
</fieldset>
