<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cinstallment extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->check_user_auth();
        $this->load->model(array('dashboard/Invoices'));
        $this->load->library('dashboard/linvoice');
        $this->load->library('dashboard/occational');
    }

    public function empdropdown(){
        $this->db->select('*');
        $this->db->from('employee_history');
        $query = $this->db->get();
        $data = $query->result();

        $list = array('' => 'Select One...');
        if (!empty($data) ) {
            foreach ($data as $value) {
                $list[$value->id] = $value->first_name." ".$value->last_name;
            }
        }
        return $list;
    }

    //Invoice List count
    public function count_invoice_list($filter = []) {
        $this->db->select('a.invoice_id');
        $this->db->from('invoice a');
        $this->db->where('a.is_installment', 1);
        if (!empty($filter['invoice_no'])) {
            $this->db->where('a.invoice', $filter['invoice_no']);
        }
        if (!empty($filter['customer_id'])) {
            $this->db->where('a.customer_id', $filter['customer_id']);
        }
        if (!empty($filter['date'])) {
            $this->db->where("STR_TO_DATE(a.date, '%m-%d-%Y')=DATE('" . $filter['date'] . "')");
        }
        $query = $this->db->count_all_results();
        return $query;
    }

    //Invoice List
    public function get_invoice_list($filter, $start, $limit) {
        $this->db->select('a.*,b.*,c.order');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->join('order c', 'c.order_id = a.order_id', 'left');
        $this->db->where('a.is_installment', 1);
        if (!empty($filter['invoice_no'] != '')) {
            $this->db->where('a.invoice', $filter['invoice_no']);
        }
        if ($filter['customer_id'] != '') {
            $this->db->where('a.customer_id', $filter['customer_id']);
        }
        if ($filter['date'] != '') {
            $this->db->where("STR_TO_DATE(a.date, '%m-%d-%Y')=DATE('" . $filter['date'] . "')");
        }
        $this->db->order_by('a.invoice', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return false;
    }

    public function manage_installment()
    {
        $this->permission->check_label('manage_sale')->read()->redirect();
        $filter = array(
            'invoice_no'    => $this->input->get('invoice_no', TRUE),
            'customer_id'   => $this->input->get('customer_id', TRUE),
            'date'          => $this->input->get('date', TRUE),
        );
        $config["base_url"]   = base_url('dashboard/Cinstallment/manage_installment');
        $config["total_rows"] = $this->count_invoice_list($filter);
        $config["per_page"]   = 20;
        $config["uri_segment"] = 4;
        $config["num_links"]  = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open']   = "<ul class='pagination'>";
        $config['full_tag_close']  = "</ul>";
        $config['num_tag_open']    = '<li>';
        $config['num_tag_close']   = '</li>';
        $config['cur_tag_open']    = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close']   = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open']   = "<li>";
        $config['next_tag_close']  = "</li>";
        $config['prev_tag_open']   = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open']  = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open']   = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $links = $this->pagination->create_links();
        $invoices_list = $this->get_invoice_list($filter, $page, $config["per_page"]);
        if (!empty($invoices_list)) {
            foreach ($invoices_list as $k => $v) {
                $invoices_list[$k]['final_date'] = $this->occational->dateConvert($invoices_list[$k]['date']);
            }
            $i = 0;
            foreach ($invoices_list as $k => $v) {
                $i++;
                $invoices_list[$k]['sl'] = $i;
            }
        }
        $this->load->model(array('dashboard/Soft_settings', 'dashboard/Customers'));
        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $data = array(
            'title'         => display('manage_invoice'),
            'invoices_list' => $invoices_list,
            'currency'      => $currency_details[0]['currency_icon'],
            'position'      => $currency_details[0]['currency_position'],
        );

        $data['module'] = "dashboard";
        $data['page']   = "installment/index";
        echo Modules::run('template/layout', $data);
    }

    //Installment Update Form
    public function installment_update_form($invoice_id)
    {
        $this->permission->check_label('manage_installment')->update()->redirect();

        $this->db->select('*');
        $this->db->from('invoice_installment');
        $this->db->where('invoice_id', $invoice_id);
        $installment_details = $this->db->get()->result_array();

        $this->db->select('a.*,b.*');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.invoice_id', $invoice_id);
        $invoice = $this->db->get()->result_array();

        $this->load->model(array('dashboard/Soft_settings', 'dashboard/Customers'));
        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $data = array(
            'title'         => display('edit_installment'),
            'installment_details' => $installment_details,
            'invoice' => $invoice[0],
            'currency'      => $currency_details[0]['currency_icon'],
            'position'      => $currency_details[0]['currency_position'],
        );

        $data['module'] = "dashboard";
        $data['page']   = "installment/edit_installment_form";
        echo Modules::run('template/layout', $data);
    }
    // Installment Update
    public function installment_update()
    {
        $this->permission->check_label('manage_sale')->update()->redirect();

        $invoice_id = $this->Invoices->update_invoice();
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect('dashboard/cinvoice/invoice_inserted_data/' . $invoice_id);
    }








    //Insert new invoice
    public function insert_invoice()
    {
        if($this->input->post('due_amount', TRUE) > 0 && $this->input->post('is_installment', TRUE) == 0){
            $this->session->set_userdata(array('error_message' => display('choose_installment_if_invoice_not_full_paid')));
            $this->index();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_id[]', display('product_id'), 'required');
        $this->form_validation->set_rules('variant_id[]', display('variant'), 'required');
        $this->form_validation->set_rules('batch_no[]', display('batch_no'), 'required');
        $this->form_validation->set_rules('employee_id', display('employee_id'), 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => display('failed_try_again')));
            $this->index();
        } else {
            $invoice_id = $this->Invoices->invoice_entry();
            $this->session->set_userdata(array('message' => display('successfully_added')));
            if ($this->input->post('pos', TRUE) === 'pos') {
                redirect('dashboard/Cinvoice/pos_invoice_inserted_data_redirect/' . $invoice_id . '?place=pos');
            } else {
                redirect('dashboard/Cinvoice/invoice_inserted_data/' . $invoice_id);
            }
        }
    }

    //Retrive right now inserted data to cretae html
    public function invoice_inserted_data($invoice_id)
    {
        $content = $this->linvoice->invoice_html_data($invoice_id);
        $this->template_lib->full_admin_html_view($content);
    }

    //Email testing for email
    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //Search Inovoice Item
    public function search_inovoice_item()
    {

        $customer_id = $this->input->post('customer_id', TRUE);
        $content = $this->linvoice->search_inovoice_item($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }

    //This function is used to Generate Key
    public function generator($lenth)
    {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }

    public function convert_number($number)
    {
        if ($number < 0) {
            $number = - ($number);
        }
        if (($number < 0) || ($number > 9999999999999)) {
            throw new Exception("Number is out of range");
        }
        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */
        $res = "";
        if ($Gn) {
            $res .= $this->convert_number($Gn) .  "Million";
        }
        if ($kn) {
            $res .= (empty($res) ? "" : " ") . $this->convert_number($kn) . " Thousand";
        }
        if ($Hn) {
            $res .= (empty($res) ? "" : " ") . $this->convert_number($Hn) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }
            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];
                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }
        if (empty($res)) {
            $res = "zero";
        }
        return $res;
    }

}