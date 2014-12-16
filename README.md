## Custom Sidebars

To integrate you will need to decide on a sidebar you want to have made dynamic. There is a single method to use:

```php
<?php dynamic_sidebar( sneek_custom_sidebar() ); ?>
```

It's always best to register a default widget area, and use this as the default:

```php
<?php dynamic_sidebar( sneek_custom_sidebar( 'default-widget-area' ) ); ?>
```

You can also get sidebars for specific pages:

```php
<?php dynamic_sidebar( sneek_custom_sidebar( 'default-widget-area', $another_post_id ) ); ?>
```