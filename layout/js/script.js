 

'use strict';
$(document).ready(function(){

    //hide login/register links when logged in
    if($('li').hasClass('logged-in')){
        $('.not-logged').hide();
    }

    // Hide/show search form in small screen
    $('.search-link').click(function (){
        $('#keyword-search').toggleClass('hide');
        $('#filter').addClass('hide');
    });

    // Hide/show filter form in small screen
    $('.filter-link').click(function (){
        $('#filter').toggleClass('hide');
        $('#keyword-search').addClass('hide');
    });

    // put search form in right sidebar
    if (window.matchMedia('(min-width: 60em)').matches)
    {
        $('#search').detach().appendTo('#right-sidebar');
        // list all applications for an ad in right sidebar in big screens
        $('.cv-btn').click(function (e){
            $('.right-sidebar h3').text('CV profiler');
            $('.right-sidebar ul').replaceWith($('.main-content section'))
            $('.right-sidebar section h3').addClass('hide');
            let url = e.target + ' .cv-profile';
            $('.main-content').load(url);
            $(this).parent().siblings().children().css('color','#0f62dc');
            $(this).css('color','black'); //change color of clicked application
            e.preventDefault();
        });
    }

    // auto close alert after 5s
    setTimeout(function () {$('.alert-msg').alert('close');}, 7000);


    // check if password input and confirm input are same
    $('#pwd-confirm').change(function (){
        if($('#pwd').val() !== $('#pwd-confirm').val()){ // if not the same disable submit btn
            $('.invalid-pwd').show('fast'); // show error message
            $(this).css('border-color','#dc3545');
            $('#signup-submit').prop('disabled',true);
        } else { // if password and password confirm are same, then hide error msg and enable submit btn
            $('.invalid-pwd').hide('fast');
            $(this).css('border-color','#198754');
            $('#signup-submit').prop('disabled',false);
        }});

    // check file format , size when uploading a new logo
    $('input[type="file"]').change(function(e){
        const file = e.target.files[0];
        const el = $('#file-info');
        const ext = file.name.split('.').pop().toLowerCase();
        if(file.size > 1000000){
            el.text("Ej laddas upp, filen överskred maxstorleken");
        }else if ($.inArray(ext, ['svg','png','jpg','jpeg']) === -1){
            el.text("Fel format!! välj format som '.svg','.png','.jpg','.jpeg'");
            el.css('color','#b30000');
        } else {
            el.text(file.name);
            $('#del-file').show();
        }});

    // hide delete file btn when clicked
    $('#del-file').click(function (){
        $(this).hide();
        $('input[type="file"]').val('');
        $('#file-info').text('');
    });
    // show delete file btn if there is a logo uploaded
    if($('.account-img').has( "img" ).length){
        $('#del-file').show();
    }

    // remove img tag from page when logo is deleted
    $('#del-file').on('click',function (){
        // if there is a logo with id=[filename]
        let id= $('.membership .account-logo-area img').attr('id');
        let url = "company.php?action=del-logo&logo-id=" + id;
        if(id){
            //send request to server to delete logo
            $.get(url,function (){
                // delete img from html
                $('.membership .account-logo-area img').remove();});}
    });

    // delete selected logo file on click on delete btn in sign up page
    $('#btn-logo-upload').on('click', function(){$('input[type="file"]').click();});

    // show company part when user click on sign up as company
    var company_info;
    $('input[type="radio"][name="account-type"]').click(function (){
        let is_checked = $('input[type="radio"][value="c"]').is(':checked');
        if(is_checked){
            if(company_info){
                company_info.appendTo('.company-info');
            }
            $('.company-info').show();
        }else {
            $('.company-info').hide();
            company_info=$('.company-info > fieldset ').detach();
        }
    });

    // hide email error message if any
    $('input[type="email"]').one('keypress',function(e){
        $('.error').hide();
    });

    // alert when clicking on delete account link
    $('#del-account').click(function (e){
        let text = "Bekräfta att du vill tar bort ditt konto";
        if (confirm(text) == false) {
            e.preventDefault();
        }});
    // hide nav and footer for admin page
    if($('.main-content.account-page').hasClass('dashboard')){
        if(!$('.main-content').hasClass('login-page')){
            $('.nav-menu').replaceWith('<div class="me-3"><a href="logout.php">Logga ut</a></div>');
        }else{
            $('.nav-menu').empty();
        }
        $('.main-footer > div').hide();
    }

});

// Disabling form submissions if there are invalid fields
// source https://getbootstrap.com/docs/5.2/forms/validation/
(function () {
    'use strict'
    window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation');
        // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })},false);
})();
