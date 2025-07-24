<?php
namespace backend\components;

class CustomToaster
{
    public static function render($type, $message)
    {
        $iconClass = '';
        $bgClass = '';

        switch ($type) {
            case 'success':
                $iconClass = 'ti-check';
                $bgClass = 'bg-success';
                break;
            case 'warning':
                $iconClass = 'fa fa-exclamation-triangle';
                $bgClass = 'bg-warning';
                break;
            case 'error':
                $iconClass = 'fa fa-exclamation-circle';
                $bgClass = 'bg-error';
                break;
        }

        return <<<HTML
        <div class="custom-toaster">
            <div class="toaster-icon color-$type">
                <i class="$iconClass"></i>
            </div>
            <div class="toaster-content $bgClass">
                <div class="toaster-heading">$type</div>
                $message
            </div>
        </div>
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('.custom-toaster').fadeOut();
                }, 3000);
            });
        </script>
HTML;
    }
}
?>