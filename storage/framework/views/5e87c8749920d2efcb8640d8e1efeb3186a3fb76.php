<?php $__env->startSection('main-wrapper'); ?>
  <section id="main-wrapper" class="main-wrapper user-account-section stripe-content">
    <div class="container-fluid">
      <h4 class="heading"><a href="<?php echo e(url('account')); ?>">Account &amp; Settings</a></h4>
      <div class="panel-setting-main-block pad-lt-50">
        <div class="panel-setting">
          <?php if(isset($bitcoin_payment) && $bitcoin_payment == 1): ?>
            <div class="row">
              <div class="col-md-5">
                <div class="bitcoin">
                  <h3 class="plan-dtl-heading">Checkout With Bitcoin !<span id="expired_label" class="label label-danger hide">Expired!!!</span><span id="timer"></span></h3>
                  <p>Amount</p>
                  <p><strong><?php echo e($bits/1.0e8); ?></strong> BTC ⇌ <strong><?php echo e($plan->amount); ?></strong> USD</p>

                  <p>Bitcoin Address</p>
                  <div id="qrcode" data-value="bitcoin:<?php echo e($new_address->address); ?>?amount=<?php echo e($bits/1.0e8); ?>"></div>
                  <input type="text" id="bitcoin_address" name="bitcoin_address" value="<?php echo e($new_address->address); ?>" readonly />
                </div>
              </div>
            </div>
            <!-- <div class="progress-bar"><div></div><span class="time"></span></div> -->
          <?php endif; ?>
          <!-- <div id="progressTimer"></div> -->
        </div>
      </div>
    </div>
  </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('stylesheets'); ?>
<link href="<?php echo e(asset('js/jquery-countdownTimer/jquery.countdownTimer.css')); ?>" rel="stylesheet" type="text/css"/>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom-script'); ?>
  <script>
    $(function() {
      $('#qrcode').qrcode({
        text  : $('#qrcode').data('value')
      });

      $('#bitcoin_address').on('click', function() {
        $(this).select();
      });

      $("#timer").countdowntimer({
        minutes: 10,
        size: "lg",
        timeUp: function() {
          $("#timer").hide();
          $('#expired_label').removeClass('hide');
            setTimeout(function() {
              document.location.href = '/account/purchaseplan';
            }, 2000);
        }
      });
    });
  </script>

  <script type="text/javascript" src="/js/jquery.qrcode/jquery.qrcode.js"></script>
  <script type="text/javascript" src="/js/jquery.qrcode/qrcode.js"></script>
  <script src="/js/js.cookie.min.js"></script>
  <script src="/js/jquery-countdownTimer/jquery.countdownTimer.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.theme', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>