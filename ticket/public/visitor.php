<script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>

<form role="form" id="form_itfuture2017_visitor" action="http://plan2017.itfuture.pl/ajax/visitor/edit" method="post">
    <input type="hidden" name="place" value="visitor" />
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group form-group-default">
                <label>Imię</label>
                <input id="visitor_firstname" type="text" class="form-control" value="" name="visitor_firstname">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group form-group-default">
                <label>Nazwisko</label>
                <input id="visitor_lastname" type="text" class="form-control" value="" name="visitor_lastname">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group form-group-default">
                <label>Firma</label>
                <input id="visitor_company" type="text" class="form-control" value="" name="visitor_company">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group form-group-default">
                <label>Stanowisko</label>
                <input id="visitor_company2" type="text" class="form-control" value="" name="visitor_company2">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group form-group-default">
                <label>Telefon</label>
                <input id="visitor_tel" type="text" class="form-control" value="" name="visitor_tel">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group form-group-default">
                <label>Email</label>
                <input id="visitor_email" type="text" class="form-control" value="" name="visitor_email">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <button type="button" id="button_itfuture2017_visitor" value="Zapisz" name="wyslij">Zapisz</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#button_itfuture2017_visitor').on('click', function() {
            $.ajax({
                method: "POST",
                url: $( "#form_itfuture2017_visitor" ).attr('action'),
                data: $( "#form_itfuture2017_visitor" ).serializeArray();
            }).done(function( msg ) {
                console.log(msg);
                alert( "Data Saved: " + msg );
            });

        });
    })
</script>