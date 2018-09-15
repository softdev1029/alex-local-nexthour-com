<?php $__env->startSection('main-wrapper'); ?>
  <section id="main-wrapper" class="main-wrapper user-account-section stripe-content">
    <div class="container-fluid">
      <h4 class="heading"><a href="<?php echo e(url('account')); ?>">Account &amp; Settings</a></h4>
      <div class="panel-setting-main-block pad-lt-50">
        <div class="panel-setting">
          <h3 class="plan-dtl-heading">Checkout</h3>
          <div class="row">
            <?php if(isset($stripe_payment) && $stripe_payment == 1): ?>  
              <div class="col-md-5">
                <?php echo Form::open(['method' => 'POST', 'action' => 'UserAccountController@subscribe', 'id' => 'payment-form']); ?>

                  <?php echo e(csrf_field()); ?>

                  <div class="form-row">
                    <div class="form-group">
                      <label for="coupon">Apply Coupon</label>
                      <input id="coupon" class="form-control" type="text" name="coupon" placeholder="Enter Your Coupon Code">
                    </div>
                    <input type="hidden" name="plan" value="<?php echo e($plan->id); ?>">
                    <label for="card-element">
                      Credit or debit card
                    </label>
                    <div id="card-element">
                      <!-- a Stripe Element will be inserted here. -->
                    </div>
                    <!-- Used to display form errors -->
                    <div id="card-errors" role="alert"></div>
                  </div>
                  <button class="payment-btn stripe"><i class="fa fa-credit-card"></i> Submit Payment</button>
                <?php echo Form::close(); ?>

              </div>
            <?php endif; ?>
            <div class="col-md-5">
              <?php if(isset($paypal_payment) && $paypal_payment == 1): ?>
                <h3 class="plan-dtl-heading">Checkout With Paypal !</h3>
                <?php echo Form::open(['method' => 'POST', 'action' => 'PaypalController@postPaymentWithpaypal']); ?>

                  <input type="hidden" name="plan_id" value="<?php echo e($plan->id); ?>">
                  <button class="payment-btn paypal-btn"><i class="fa fa-credit-card"></i> Pay Via Paypal</button>
                <?php echo Form::close(); ?>

              <?php endif; ?>
              <?php if(isset($payu_payment) && $payu_payment == 1): ?>
                <div class="payu">
                  <h3 class="plan-dtl-heading">Checkout With PayUmoney !</h3>
                  <?php echo Form::open(['method' => 'POST', 'action' => 'PayuController@payment']); ?>

                    <input type="hidden" name="plan_id" value="<?php echo e($plan->id); ?>">
                    <button class="payment-btn payu-btn"><i class="fa fa-credit-card"></i> Pay Via Payu</button>
                  <?php echo Form::close(); ?>

                </div>
              <?php endif; ?>
              <?php if(isset($bitcoin_payment) && $bitcoin_payment == 1): ?>
                <div class="bitcoin">
                  <h3 class="plan-dtl-heading">Checkout With Bitcoin !</h3>
                  <?php echo Form::open(['method' => 'POST', 'action' => 'BitcoinPaymentController@payment']); ?>

                    <input type="hidden" name="plan_id" value="<?php echo e($plan->id); ?>">
                    <button class="payment-btn bitcoin-btn"><i class="fa fa-credit-card"></i> Pay Via Bitcoin</button>
                  <?php echo Form::close(); ?>

                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>  
      </div>
    </div>
  </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom-script'); ?>
  <script>
    $(function(){
      $('.paypal-btn').on('click', function(){
        $('.paypal-btn').addClass('load');
      }); 
      $('.payu-btn').on('click', function(){
        $('.payu-btn').addClass('load');
      });

      $('.bitcoin-btn').on('click', function(){
        $(this).addClass('load');
      });
    });
    // Create a Stripe client
    var stripe = Stripe('<?php echo e(env('STRIPE_KEY')); ?>');
    // Create an instance of Elements
    var elements = stripe.elements();
    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
      base: {
        color: '#32325d',
        lineHeight: '18px',
        fontFamily: '"Lato", sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#aab7c4'
        }
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
      }
    };
    // Create an instance of the card Element
    var card = elements.create('card', {
      style: style,
      hidePostalCode: true
    });
    // Add an instance of the card Element into the `card-element` <div>
    card.mount('#card-element');
    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
      var displayError = document.getElementById('card-errors');
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });
    // Handle form submission
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();
      stripe.createToken(card).then(function(result) {
        if (result.error) {
          // Inform the user if there was an error
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = result.error.message;
        } else {
          // Send the token to your server
          $('.payment-btn.stripe').addClass('load');
          stripeTokenHandler(result.token);
        }
      });
    });
    function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      var form = document.getElementById('payment-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);
      // Submit the form
      form.submit();
    }
  </script>

  <script type="text/javascript" src="/js/jquery.qrcode/jquery.qrcode.js"></script>
  <script type="text/javascript" src="/js/jquery.qrcode/qrcode.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.theme', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>