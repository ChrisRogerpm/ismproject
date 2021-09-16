<div class="modal fade" id="ModalImportarProducto" tabindex="-1" role="dialog" aria-labelledby="ModalImportarProducto" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="txtTituloModalItem">IMPORTAR PRODUCTOS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="frmImportarProducto">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">SUBIR ARCHIVO:</label>
                                <div></div>
                                <div class="custom-file">
                                    <input type="file" name="productoExcel" id="productoExcel" class="custom-file-input">
                                    <label class="custom-file-label" for="customFile">Elegir archivo...</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-window-close"></i>CERRAR
                </button>
                <button type="button" class="btn btn-primary" id="btnImportarProducto"><i class="fa fa-plus-square"></i>IMPORTAR PRODUCTO
                </button>
            </div>
        </div>
    </div>
</div>
