Upgrading Instructions for Admin pack Extension for Yii 2
=========================================================

!!!IMPORTANT!!!

The following upgrading instructions are cumulative. That is,
if you want to upgrade from version A to version C and there is
version B between A and C, you need to following the instructions
for both A and B.

Upgrade from admin 1.1.0
------------------------

* Version constraint for "yiisoft/yii2" package has been raised to "~2.1.0". Make sure your code
  matches this version of the Yii framework. 

* Action `FlushCache` has been renamed to `ClearCache`. Make sure you are using correct class name
 for this action setup.


Upgrade from 1.0.3
------------------

* PHP requirements were raised to 5.6. Make sure your code is updated accordingly.
