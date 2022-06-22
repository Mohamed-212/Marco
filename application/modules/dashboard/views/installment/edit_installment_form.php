<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Customer js php -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/customer.js.php"></script>

<!-- Edit Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('edit_installment') ?></h1>
            <small><?php echo display('edit_installment') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('installment') ?></a></li>
                <li class="active"><?php echo display('edit_installment') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Invoice report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('edit_installment') ?></h4>
                        </div>
                    </div>
                    <?php echo form_open('dashboard/Cinstallment/installment_update', array('class' => 'form-vertical', 'id' => 'validate')) ?>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-sm-6" id="">
                                <div class="form-group row">
                                    <label for="customer_name"
                                           class="col-sm-4 col-form-label"><?php echo display('customer_name') ?> </label>
                                    <div class="col-sm-8">
                                        <input type="text" value="<?php echo html_escape($invoice['customer_name']); ?>"
                                               class="form-control customerSelection" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6" id="">
                                <div class="form-group row">
                                    <label for="customer_name"
                                           class="col-sm-4 col-form-label"><?php echo display('invoice_no') ?> </label>
                                    <div class="col-sm-8">
                                        <input type="text" value="<?php echo html_escape($invoice['invoice']); ?>"
                                               class="form-control customerSelection" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt_10">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center"><?php echo display('due_amount') ?></th>
                                    <th class="text-center"><?php echo display('due_date') ?></th>
                                    <th class="text-center"><?php echo display('paid_amountt') ?></th>
                                    <th class="text-center"><?php echo display('paid_date') ?></th>
                                    <th class="text-center"><?php echo display('payment_type') ?></th>
                                    <th class="text-center"><?php echo display('employee_name') ?></th>
                                    <th class="text-center"><?php echo display('status') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($installment_details) {
                                        foreach ($installment_details as $value) { ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="text" name="amount" required
                                               class="form-control" readonly
                                               value="<?php echo html_escape($value['amount']) ?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control datepicker" name="due_date" required
                                               value="<?php echo html_escape($value['due_date']) ?>" readonly>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="payment_amount"
                                               class="form-control" value="" max="<?php echo html_escape($value['amount']) ?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control datepicker" name="payment_date"
                                               value="<?php echo set_value('date', date("Y-m-d")) ?>" readonly>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="payment_amount"
                                               class="form-control" value="" max="<?php echo html_escape($value['amount']) ?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="payment_amount"
                                               class="form-control" value="" max="<?php echo html_escape($value['amount']) ?>">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="payment_amount"
                                               class="form-control" value="" max="<?php echo html_escape($value['amount']) ?>">
                                    </td>
                                </tr>
                                <?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo MOD_URL . 'dashboard/assets/js/installment.js'; ?>"></script>