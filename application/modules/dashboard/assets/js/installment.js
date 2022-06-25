function changPaymentType(x){
    var elem = $(x).val();
    var check_no = $(x).next().next().next().next().next();
    if(elem == "wire transfer" || elem == "check"){
        if(check_no.css('display') == "none"){
            check_no.css('display', 'inherit');
        }
    }
    else{
        check_no.css('display', 'none');
    }
}