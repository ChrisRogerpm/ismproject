<div class="modal fade" id="ModalImportarBonificacion" tabindex="-1" role="dialog" aria-labelledby="ModalImportarBonificacion" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="txtTituloModalItem">IMPORTAR BONIFICACIÓN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="frmImportarBonificacion">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">SUBIR ARCHIVO:</label>
                                <div></div>
                                <div class="custom-file">
                                    <input type="file" name="archivoBonificacion" id="archivoBonificacion" class="custom-file-input">
                                    <label class="custom-file-label" for="customFile">Elegir archivo...</label>
                                </div>
                                <span class="form-text text-muted">Formato aceptado: .csv</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-window-close"></i>CERRAR
                </button>
                <button type="button" class="btn btn-primary" id="btnImportarBonificacion"><i class="fa fa-plus-square"></i>IMPORTAR BONIFICACIÓN
                </button>
            </div>
        </div>
    </div>
</div>
