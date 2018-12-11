<?= $this->partial('layouts/menu') ?>
<div class="ui grid">
    <div class="six column row">

      <div class="right floated column">
        <button class="ui  primary button" id="btnAdd" name="btnAdd">
            <i class="icon add"></i>
            Add Album
          </button>

      </div>
  </div>

  <div class="row">
    <table class="ui celled table" id="tableAlbums">
      <thead>
        <th>Name</th><th>Author</th><th>Genre</th><th>Options</th>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>


<!--MODAL ADD ALBUM-->
<div class="ui modal mini" id="modalNew">
  <i class="close icon"></i>
  <div class="header">
    New Album
  </div>
  <div class="content">
  <form class="ui form" name="formAdd" id="formAdd" method="post">
    <div class="ui form">
      <div class="field">

          <input type="text" id="nameA" name="nameA" placeholder="Name">
      </div>

        <div class="field">

            <input type="text" id="autor" name="autor" placeholder="Author">
        </div>
          <div class="field">

              <select class="ui search dropdown" name="genre_id" id="genre_id" >
                      <?php foreach ($generos as $genero) { ?>
                          <option class="item" value="<?= $genero->id ?>"><?= $genero->name ?></option>
                      <?php } ?>
                </select>
          </div>
    </div>
    <input type="hidden" name="idEdit" id="idEdit" value="">
    <input type="hidden" name="option" id="option" value="new">
  </form>
</div>
  <div class="actions">
    <div class="ui deny button" id="btnCancel" name="btnCancel">Cancel</div>
    <div class="ui positive button " id="btnSave" name="btnSave">OK</div>
  </div>
</div>

<!--Modal delete-->

<div class="ui basic modal delete" id="modalDelete">
  <div class="ui icon header">
    <i class="archive icon"></i>
    Eliminar Álbum
  </div>
  <div class="content">
    <p>¿Está seguro que desea eliminar el Álbum?</p>
  </div>
  <div class="actions">
    <div class="ui red basic cancel inverted button">
      <i class="remove icon"></i>
      NO
    </div>
    <div class="ui green ok inverted button">
      <i class="checkmark icon"></i>
      SI
    </div>
  </div>
</div>

<script type="text/javascript">

$(document).ready( function () {

  $('select.dropdown')
    .dropdown()
  ;
  //DataTable definition
var tableA = $('#tableAlbums').DataTable(
  {
    processing:false,
    serverSide:false,
    ajax: {
                "url": "<?php echo $this->url->get('Albums/datatable') ?>",
                "type": "POST",
            },
            columns: [
                {data: "name" },
                {data:"author"},
                {data:"genre"},

                {
                   sortable: false,
                   "render": function ( data, type, full, meta ) {
                       return '<div class="ui buttons"><button class="ui positive button" onclick="editA('+full.id+')"  > <i class="icon edit"></i> </button> <div class="or" data-text="O"></div> <button class="ui negative button" onclick="deleteA('+full.id+')"><i class="icon erase"></i> </button> </div>';
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

      var modal= $('#modalNew').modal(
        {
        closable  : false,
        onDeny    : function(){
                    return false;
                    },
        onApprove : function() {
                    save(tableA);
                    }
        }).modal("show");
  });

});//CLOSE READY function

function save(tableA)
{
  $.ajax({
        method: "POST",
          url: "<?php echo $this->url->get('Albums/save') ?>",
        data: $("#formAdd").serializeArray(),
      }).done(function( msg ) {
          console.log(msg);
          tableA.ajax.reload();
        });
}

//Show modal ADD
function showModalAdd(){
  var tableA = $("#tableAlbums").DataTable();
  var modal= $('#modalNew').modal(
    {
    closable  : true,
    //onDeny    : function(){    return false;  },
    onApprove : function() {
                save(tableA);
                }
    }).modal("show");
}

//Search byId and Show Modal

function editA(id)
{
//alert(id);
  $.ajax({
        method: "POST",
          url: "<?php echo $this->url->get('Albums/getById') ?>",
        data: { id:id }
      }).done(function( data )
        {
          $('#nameA').val(data.album[0].name);
          $('#autor').val(data.album[0].author);
          $('#genre_id').val(data.album[0].genre_id).change();
          $('#option').val('edit');
          $('#idEdit').val(data.album.id);
          showModalAdd();
        });
}
//function to Delete Genre  ID
function deleteA(idAlbum)
{  //Confirm delete and submit
    $('#modalDelete')
      .modal({
        //closable  : false,
        onDeny    : function(){
          //return true;
        },
        onApprove : function() {
          //Open call ajax
//alert(idAlbum);
          $.ajax({
            type: "POST",
            url: "<?php echo $this->url->get('Albums/delete') ?>",
            data:{'id':idAlbum}
          }).done(function(data)
          {
            var tableA = $("#tableAlbums").DataTable();
            tableA.ajax.reload();
          });
          //Close call ajax
        }
      }).modal('show');
}

</script>
