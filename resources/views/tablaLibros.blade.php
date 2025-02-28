<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>
    <div class="container">
        <br />
        <h3 align="center">Tabla de libros</h3>
        <br />
            <div align="right">
                <button type="button" name="create_libro" id="create_libro" class="btn btn-success btn-sm">Añadir libro</button>
            </div>
        <br />
            <div class="table-responsive">
                <table id="libros_table" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th width="35%">Titulo</th>
                        <th width="35%">Sinopsis</th>
                        <th width="35%">Numero de paginas</th>
                            <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        <br />
        <br />
    </div>

    <div id="formModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Record</h4>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form method="post" id="sample_form" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-4" >Titulo: </label>
                            <div class="col-md-8">
                                <input type="text" name="titulo" id="titulo" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Sinopsis:  </label>
                            <div class="col-md-8">
                                <input type="text" name="sinopsis" id="sinopsis" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Número de páginas: </label>
                            <div class="col-md-8">
                                <input type="text" name="numPaginas" id="numPaginas" class="form-control" />
                            </div>
                        </div>
                        <br />
                        <div class="form-group" align="center">
                            <input type="hidden" name="action" id="action" value="Añadir" />
                            <input type="hidden" name="hidden_id" id="hidden_id" />
                            <input type="submit" name="action_button" id="action_button" class="btn btn-warning" value="Añadir" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title">Confirmacion</h2>
                </div>
                <div class="modal-body">
                    <h4 align="center" style="margin:0;">Estás seguro de que quieres eliminar el libro</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Aceptar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            $('#libros_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('libros.index') }}",
                },
                columns: [
                    {
                        data: 'titulo',
                        name: 'Titulo'
                    },
                    {
                        data: 'sinopsis',
                        name: 'Sinopsis'
                    },
                    {
                        data: 'numPaginas',
                        name: 'Numero de paginas',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ]
            });
        });

        $('#create_libro').click(function(){
            $('.modal-title').text('Añadir un nuevo libro');
            $('#action_button').val('Añadir');
            $('#action').val('Añadir');
            $('#form_result').html('');

            $('#formModal').modal('show');
        });

        $('#sample_form').on('submit', function(event){
            event.preventDefault();
            var action_url = '';

            if($('#action').val() == 'Añadir')
            {
                action_url = "{{ route('libros.store') }}";
            }
            if($('#action').val() == 'Edit')
            {
                action_url = "{{ route('libros.update') }}";
            }
            $.ajax({
                url: action_url,
                method:"POST",
                data:$(this).serialize(),
                dataType:"json",
                success:function(data)
                {
                    var html = '';
                    if(data.errors)
                    {
                        html = '<div class="alert alert-danger">';
                        for(var count = 0; count < data.errors.length; count++)
                        {
                            html += '<p>' + data.errors[count] + '</p>';
                        }
                        html += '</div>';

                    }
                    if(data.success)
                    {
                        html = '<div class="alert alert-success">' + data.success + '</div>';
                        $('#sample_form')[0].reset();
                        $('#user_table').DataTable().ajax.reload();
                    }
                    $('#form_result').html(html);
                }
            });
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax({
                url :"/libros/"+id+"/edit",
                dataType:"json",
                success:function(data)
                {
                    $('#titulo').val(data.result.titulo);
                    $('#sinopsis').val(data.result.sinopsis);
                    $('#numPaginas').val(data.result.numPaginas);
                    $('#hidden_id').val(id);
                    $('.modal-title').text('Editar libro');
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });

        var libro_id;

        $(document).on('click', '.delete', function(){
            libro_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok_button').click(function(){
            $.ajax({
                url:"libros/destroy/"+libro_id,
                beforeSend:function(){
                    $('#ok_button').text('Eliminando...');
                },
                success:function(libro)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#libros_table').DataTable().ajax.reload();
                        alert('Libro eliminado');
                    }, 2000);
                }
            });
        });
    </script>
    </body>
</html>
