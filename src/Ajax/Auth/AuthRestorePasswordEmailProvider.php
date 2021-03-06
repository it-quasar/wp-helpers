<?php

declare(strict_types=1);
/**
 *  This file is part of the it-quasar/wp-helpers library.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace ItQuasar\WpHelpers\Ajax\Auth;

use WP_User;

/**
 * Провайдер email'ов для восстановления пароля.
 */
class AuthRestorePasswordEmailProvider
{
  /**
   * Возвращает email для восстановления пароля пользователя.
   *
   * @param WP_User $user             Новый пользователь
   * @param string  $resetPasswordUrl URL для сброса (задания нового) пароля
   *
   * @return array Email: [to: string, subject: string, message: string, headers: string]
   */
  public function restorePasswordUserEmail(WP_User $user, string $resetPasswordUrl): array
  {
    $blogName = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $switchedLocale = switch_to_locale(get_user_locale($user));

    $message = __('Someone has requested a password reset for the following account:')."\r\n\r\n";
    // translators: %s: site name
    $message .= sprintf(__('Site Name: %s'), $blogName)."\r\n\r\n";
    // translators: %s: user login
    $message .= sprintf(__('Username: %s'), $user->user_login)."\r\n\r\n";
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.')."\r\n\r\n";
    $message .= __('To reset your password, visit the following address:')."\r\n\r\n";
    $message .= "<$resetPasswordUrl>\r\n";

    $email = [
      'to' => $user->user_email,
      // translators: Password change notification email subject. %s: Site title
      'subject' => sprintf(__('[%s] Password Reset'), $blogName),
      'message' => $message,
      'headers' => '',
    ];

    if ($switchedLocale) {
      restore_previous_locale();
    }

    return $email;
  }
}
