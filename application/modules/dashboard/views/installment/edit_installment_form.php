<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Customer js php -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/customer.js.php"></script>

<style>
    .select2 {
        margin-top: 10px;
    }
</style>

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
                                        <input type="hidden" value="<?php echo html_escape($invoice['invoice']); ?>"
                                               name="invoice_id">
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
                                    <th class="text-center"><?php echo display('payment_date') ?></th>
                                    <th class="text-center"><?php echo display('payment_type') ?></th>
                                    <th class="text-center"><?php echo display('employee_name') ?></th>
                                    <th class="text-center"><?php echo display('status') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $status = array(
                                    'pending' => display('pending'),
                                    'collected' => display('collected'),
                                );
                                $payment_type = array(
                                    'cash' => display('cash'),
                                    'pos' => display('pos'),
                                    'wire transfer' => display('wire transfer'),
                                    'check' => display('check'),
                                );


                                if ($installment_details) {
                                    foreach ($installment_details as $value) {
                                        $readonly = '';
                                        if ($value['status']) {
                                            $readonly = 'readonly';
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="text" name="amount[]"
                                                       class="form-control" readonly
                                                       value="<?php echo html_escape($value['amount']) ?>">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control datepicker"
                                                       value="<?php echo html_escape($value['due_date']) ?>" disabled>
                                                <input type="hidden" class="form-control datepicker" name="due_date[]"
                                                       value="<?php echo html_escape($value['due_date']) ?>">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" name="payment_amount[]"
                                                       class="form-control" value=""
                                                       max="<?php echo html_escape($value['payment_amount']) ?>" <?php echo html_escape($readonly) ?>>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control datepicker"
                                                       value="<?php echo set_value('date', date("Y-m-d")) ?>" disabled>
                                                <input type="hidden" class="form-control datepicker" name="payment_date[]"
                                                       value="<?php echo set_value('date', date("Y-m-d")) ?>">
                                            </td>
                                            <td class="text-center">
                                                <div style="display: flex;flex-direction: column">
                                                    <?php echo form_dropdown('payment_type[]', $payment_type, $value['payment_type'], "onchange='changPaymentType(this);' class='form-control payment_type' $readonly ") ?>
                                                    <select class="form-control account" style="margin-top: 10px;"
                                                            name="account[]">
                                                        <option value=""></option>
                                                        <?php
                                                        if ($payment_info) {
                                                            foreach ($payment_info as $payment_method) {
                                                                ?>
                                                                <option
                                                                        value="<?php echo html_escape($payment_method->HeadCode); ?>"
                                                                    <?php echo html_escape($readonly) ?>
                                                                    <?php
                                                                    if ($payment_method->HeadCode == $value['account']) {
                                                                        echo 'selected';
                                                                    }
                                                                    ?> >
                                                                    <?php echo html_escape($payment_method->HeadName); ?>
                                                                </option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <input class="form-control text-center account_no" style="margin-top: 10px;"
                                                           type="text" name="account_no[]"
                                                           placeholder="<?php echo display('account_no') ?>"
                                                           value="<?php echo html_escape($value['account_no']) ?>">
                                                    <?php if ($value['status']) { ?>
                                                        <input class="form-control text-center"
                                                               style="margin-top: 10px;" type="text" name="check_no"
                                                               placeholder="<?php echo display('check_no') ?>"
                                                               value="<?php echo html_escape($value['check_no']) ?>">
                                                    <?php }else{ ?>
                                                        <input class="form-control text-center check_no"
                                                               style="margin-top: 10px; display: none;" type="text" name="check_no[]"
                                                               placeholder="<?php echo display('check_no') ?>">
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php echo form_dropdown('employee_id', $employee, $value['employee_id'], "class='form-control employee_id' $readonly ") ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo form_dropdown('status', $status, $value['status'], "class='form-control status' $readonly ") ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <input type="submit" value="<?php echo display('save') ?>"
                                        class="btn btn-large btn-success">
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo MOD_URL . 'dashboard/assets/js/installment.js'; ?>"></script>