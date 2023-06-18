# CHANGELOG

## 2.0.0 - 2023-06-18

* Upgraded to `ssofy/php-sk:2.0.0`.
* Removed SSO Server functionality.
* New Facade methods for various operations.
* New config file structure.
* Listener for `TokenDeleted` event.
* Added the route for logout (`/sso/logout?everywhere=0&redirect_uri=...`).
* Added the route for social login (`/sso/social/google`).
* Bug Fixes.

## 1.0.4 - 2023-06-02

* Fixed the issue with event payload validation.
* Fixed the issue with `user_add` and `user_update`.
* Fixed the issue with empty `name` attribute.

## 1.0.3 - 2023-06-02

* Improved user search functionality using login, email, and phone.

## 1.0.2 - 2023-06-01

* Fixed the issue with `email_verified` and `phone_verified` claims.

## 1.0.1 - 2023-06-01

* Handled `user_updated` (profile updates) and `user_added` (registration).
* Added config options for authentication methods.
* Fixed the issue with passwordless authentication.

## 1.0.0 - 2023-03-24

* First Release.
