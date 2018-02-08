<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 03/09/2017
 * Time: 23:15
 */
?>
<tr>
	<td><label for="height_<?php echo esc_attr( $field['id'] ) ?>"><?php _e( "Height","gfirem_webcam-locale" ) ?></label></td>
<td>
    <label for="height_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( __( "Height of the live camera viewer in pixels, by default '150'. ",'gfirem_webcam-locale' ) ) ?></label>

    <input type="number" name="field_options[height_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['height'])?>"  id="height_<?php echo esc_attr( $field['id'] ) ?>">
</td>

</tr>
<tr>
    <td><label for="width_<?php echo esc_attr( $field['id'] ) ?>"><?php _e( "Width" ,'gfirem_webcam-locale') ?></label></td>
    <td>
        <label for="width_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( __( "Width of the live camera viewer in pixels, by default '300'. ",'gfirem_webcam-locale' ) ) ?></label>

        <input type="number" name="field_options[width_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['width'])?>"  id="width_<?php echo esc_attr( $field['id'] ) ?>">
    </td>

</tr>

<tr>
    <td><label for="fps_<?php echo esc_attr( $field['id'] ) ?>"><?php _e( "FPS" ,'gfirem_webcam-locale') ?></label></td>
    <td>
        <label for="width_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( __( "Set the desired fps (frames per second) capture rate, by default '30'. ",'gfirem_webcam-locale' ) ) ?></label>

        <input type="number" name="field_options[fps_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['fps'])?>"  id="fps_<?php echo esc_attr( $field['id'] ) ?>">
    </td>

</tr>
<tr>
    <td><label for="jpeg_quality_<?php echo esc_attr( $field['id'] ) ?>"><?php _e( "Image Quality" ,'gfirem_webcam-locale') ?></label></td>
    <td>
        <label for="jpeg_quality_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php echo esc_attr( __( "This is the desired quality, from 0 (worst) to 100 (best), by default '90'. ",'gfirem_webcam-locale' ) ) ?></label>

        <input type="number" name="field_options[jpeg_quality_<?php echo esc_attr( $field['id'] ) ?>]" value="<?php echo esc_attr($field['jpeg_quality'])?>"  id="jpeg_quality_<?php echo esc_attr( $field['id'] ) ?>">
    </td>

</tr>


