<?php $__env->startSection('main-wrapper'); ?>
  <!-- main wrapper -->
  <section id="main-wrapper" class="main-wrapper home-page user-account-section">
    <div class="container-fluid">
      <h4 class="heading">Invoice Details</h4>
      <ul class="bradcump">
        <li><a href="<?php echo e(url('account')); ?>">Dashboard</a></li>
        <li>/</li>
        <li>Invoice Details</li>
      </ul>
      <div class="panel-setting-main-block billing-history-main-block">
        <div class="container">
          <h4 class="plan-dtl-heading">Billing History</h4>
          <div class="billing-history-block table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Package</th>
                  <th>Service Period</th>
                  <th>Payment Method</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php if(isset($invoices) && $invoices != null && count($invoices->data) > 0): ?>
                  <?php $__currentLoopData = $invoices->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                      <td><?php echo e(date("d/m/Y", $invoice->date)); ?></td>
                      <td><?php echo e($invoice->lines->data[0]->plan->id); ?></td>
                      <td><?php echo e(date("d/m/y", $invoice->lines->data[0]->period->start)); ?> to <?php echo e(date("d/m/y", $invoice->lines->data[0]->period->end)); ?></td>
                      <td>Stripe</td>
                      <td><i class="<?php echo e($currency_symbol); ?>"></i><?php echo e($invoice->total/100); ?></td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                <?php if(isset($paypal_subscriptions) && count($paypal_subscriptions) > 0): ?>
                  <?php $__currentLoopData = $paypal_subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                      $from = Carbon\Carbon::parse($item->subscription_from);
                      $from = $from->toDateString();
                      $to = Carbon\Carbon::parse($item->subscription_to);
                      $to = $to->toDateString();
                    ?>
                    <tr>
                      <td><?php echo e($item->created_at->toDateString()); ?></td>
                      <td><?php echo e($item->plan ? $item->plan->name : 'N/A'); ?></td>
                      <td><?php echo e($from); ?> to <?php echo e($to); ?></td>
                      <td><?php echo e(ucfirst($item->method)); ?></td>
                      <td><i class="<?php echo e($currency_symbol); ?>"></i> <?php echo e($item->price); ?></td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end main wrapper -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.theme', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>