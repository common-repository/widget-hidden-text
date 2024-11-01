<?php
/*
Plugin Name: Виджет - скрытый текст
Plugin URI: http://to-inform.com
Description: Плагин-виджет позволяет размещать на сайте скрытый текст. Поддерживает Text, HTML, CSS, JavaScript, Flash, PHP. Например Вы можете разместить ссылки из sape  и не испортить внешний вид сайта.
Author: Alexey Ra
Author URI: http://to-inform.com
License: GPLv2
*/

//создаем класс который наследует WP_Widget
class sekretTextWidget extends WP_Widget 
{
  function sekretTextWidget() 
  {
    $widget_ops = array('classname' => 'widget_text', 'description' => __('Разместить скрытый текст. Поддерживает Text, HTML, CSS, JavaScript, Flash, PHP.'));
    $control_ops = array('width' => 100, 'height' => 100);
    $this->WP_Widget('sekretTextWidget', __('Скрытый текст'), $widget_ops, $control_ops);
  }

  function widget( $args, $instance ) 
  {
    extract($args);
    $text = apply_filters( 'widget_text', $instance['text'], $instance );
?>
        
<style>
  .sekrets {display:none;}
</style>

<div class="textwidget">
  <p class="sekrets">
<?php
    eval("?>".$text."<?php "); 
?>
  </p>
</div>
            
<?php
  }
    
  function update( $new_instance, $old_instance ) 
  {
      $instance = $old_instance;

      if ( current_user_can('unfiltered_html') )
          $instance['text'] =  $new_instance['text'];
      else
          $instance['text'] = wp_filter_post_kses( $new_instance['text'] );
          return $instance;
  }

  function form($instance) 
  {
        $instance = wp_parse_args( (array) $instance, array('text' => '' ) );
        $text = format_to_edit($instance['text']);
?>


  <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Content:'); ?></label>
  <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?>  </textarea>
  <p class="credits">
      <small>Разработано: <a href="http://to-inform.com">Alexey Ra</a></small>
   </p>
   
<?php
  }
}


function sekretTextWidgetInit() 
{
    register_widget('sekretTextWidget');
}

add_action('widgets_init', 'sekretTextWidgetInit');
?>