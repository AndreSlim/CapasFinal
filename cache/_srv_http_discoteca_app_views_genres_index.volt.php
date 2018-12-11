<br>
<div class="ui blue inverted menu">
  <a class="item" href="genres">
    <i class="music icon"></i> Genres
  </a>
  <a class="item" href="albums">
    <i class="window restore icon"></i> Albums
  </a>
</div>

<div class="ui grid">
    <div class="four column row">

      <div class="right floated column">
        <button class="ui  primary button" id="btnAdd" name="btnAdd">
            <i class="icon add"></i>
            Add Genre
          </button>

      </div>
  </div>

  <div class="row">
    <table class="ui celled table" id="tableGenres">
      <thead>
        <th>Name</th><th>Options</th>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>




<div class="ui modal mini" id="modalNew">
  <i class="close icon"></i>
  <div class="header">
    New Genre
  </div>
  <form class="ui form" name="formAdd" id="formAdd" method="post">
    <div class="field">
      
      <br>
      <p><input type="text" id="nameG" name="nameG" placeholder="Name"></p>

    </div>
    <input type="hidden" name="idEdit" id="idEdit" value="">
    <input type="hidden" name="option" id="option" value="new">
  </form>
  <div class="actions">
    <div class="ui deny button" id="btnCancel" name="btnCancel">Cancel</div>
    <div class="ui positive button " id="btnSave" name="btnSave">OK</div>
  </div>
</div>

<!--Modal new producto-->

<div class="ui basic modal delete" id="modalDelete">
  <div class="ui icon header">
    <i class="archive icon"></i>
    Eliminar Genero
  </div>
  <div class="content">
    <p>¿Está seguro que desea eliminar el Genero?</p>
  </div>
  <div class="actions">
    <div class="ui red basic cancel inverted button">
      <i class="remove icon"></i>
      No
    </div>
    <div class="ui green ok inverted button">
      <i class="checkmark icon"></i>
      Si
    </div>
  </div>
</div>

<script type="text/javascript">

$(document).ready( function () {

  //DataTable definition
var tableG = $('#tableGenres').DataTable(
  {
    processing:false,
    serverSide:false,
    ajax: {
                "url": "<?php echo $this->url->get('genres/datatable') ?>",
                "type": "POST",
            },
            columns: [
                {data: "name" },
                {
                   sortable: false,
                   "render": function ( data, type, full, meta ) {
                       return '<div class="ui buttons"><button class="ui positive button" onclick="editG('+full.id+')"  > <i class="icon edit"></i> </button> <div class="or" data-text="O"></div> <button class="ui negative button" onclick="deleteG('+full.id+')"><i class="icon erase"></i> </button> </div>';
                   }
               }
              ],
       "language": {
           "lengthMenu": "Mostrar _MENU_ resultados por pagina",
           "zeroRecords": "Ninguna coincidencia - Intente nuevamente",
           "info": "Mostrando pagina _PAGE_ de _PAGES_",
           "infoEmpty": "Sin información disponible",
           "search":"Buscar:",
           "infoFiltered": "(filtrado del total de _MAX_ registros)"
       }
   }
);//Close DataTable

  $("#btnAdd").click(function ()
  {
    $('#option').val('new');
    $('#idEdit').val('');
      showModalAdd();
  });

});//CLOSE READY function

//Show modal ADD
function showModalAdd(){
  var modal= $('#modalNew').modal(
    {
    closable  : true,
    //onDeny    : function(){    return false;  },
    onApprove : function() {
                save();
                }
    }).modal("show");
}

//Save DATA Form AJAX
function save()
{
/*
 */


  var tableG = $("#tableGenres").DataTable();
  var formulario = $("#formAdd").serialize();
//alert(formulario);
  $.ajax({
        method: "POST",
          url: "<?php echo $this->url->get('genres/save') ?>",
        data: formulario
      }).done(function( msg ) {
        console.log(msg);
          tableG.ajax.reload();
        });


}
//Search byId and Show Modal
function editG(id)
{
 //alert(formulario); 
  $.ajax({
        method: "POST",
          url: "<?php echo $this->url->get('genres/getById') ?>",
        data: { id:id }
      }).done(function( data )
        {
          $('#nameG').val(data.genre.name);
          $('#option').val('edit');
          $('#idEdit').val(data.genre.id);
          showModalAdd();
        });
}
//function to Delete Genre  ID
function deleteG(idGenre)
{  //Confirm delete and submit
    $('#modalDelete')
      .modal({
        //closable  : false,
        onDeny    : function(){
          //return true;
        },
        onApprove : function() {
          //Open call ajax
//alert(idGenre);
          $.ajax({
            type: "POST",
            url: "<?php echo $this->url->get('genres/delete') ?>",
            data:{id:idGenre}
          }).done(function(data)
          {
//alert(data);
            var tableG = $("#tableGenres").DataTable();
            tableG.ajax.reload();
          });
          //Close call ajax
        }
      }).modal('show');
}

</script>
