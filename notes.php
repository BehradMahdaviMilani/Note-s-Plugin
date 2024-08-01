<?php
/*
Plugin Name: Notes
Plugin URI: #
Description: این افزونه به ادمین اجازه می‌دهد تا یادداشت‌های خصوصی برای کاربران بنویسد که تنها برای ادمین قابل مشاهده باشد.
Version: 1.0
Author: Behrad Mahdavi Milani
Author URI: https://ibehrad.ir
License: GPL2
*/

// افزودن فیلد یادداشت به صفحه پروفایل کاربری
function add_admin_only_note_field( $user ) {
    $current_user = wp_get_current_user();
    if ( in_array( 'supervisor', $current_user->roles ) || in_array( 'consultant', $current_user->roles ) ) { ?>
        <h3>یادداشت ادمین برای نقش‌های Supervisor و Consultant</h3>
        <table class="form-table">
            <tr>
                <th><label for="admin_only_note">یادداشت:</label></th>
                <td>
                    <textarea id="admin_only_note" name="admin_only_note" rows="5" cols="30"><?php echo esc_textarea( get_user_meta( $user->ID, 'admin_only_note', true ) ); ?></textarea>
                    <p class="description">این یادداشت فقط برای نقش‌های Supervisor و Consultant قابل مشاهده است.</p>
                </td>
            </tr>
        </table>
    <?php }
}
add_action( 'show_user_profile', 'add_admin_only_note_field' );
add_action( 'edit_user_profile', 'add_admin_only_note_field' );

// ذخیره یادداشت در پروفایل کاربری
function save_admin_only_note_field( $user_id ) {
    $current_user = wp_get_current_user();
    if ( in_array( 'supervisor', $current_user->roles ) || in_array( 'consultant', $current_user->roles ) ) {
        update_user_meta( $user_id, 'admin_only_note', $_POST['admin_only_note'] );
    }
}
add_action( 'personal_options_update', 'save_admin_only_note_field' );
add_action( 'edit_user_profile_update', 'save_admin_only_note_field' );

// حذف امکان مشاهده یادداشت برای کاربران غیر ادمین
function remove_admin_only_note_for_non_admin_users( $output, $profileuser ) {
    $current_user = wp_get_current_user();
    if ( ! in_array( 'supervisor', $current_user->roles ) && ! in_array( 'consultant', $current_user->roles ) ) {
        $output = '';
    }
    return $output;
}
add_filter( 'user_profile', 'remove_admin_only_note_for_non_admin_users', 10, 2 );

