@include('head')
<body>
<div id="wrapper">
    <!-- Navigation -->
    @include('left')
    <div id="page-wrapper">
        <div class="col-md-12 graphs">
            <div class="xs">
                <h3>Validation</h3>
                <div class="well1 white">
                    <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern" method="post" action="/admin/video/add" enctype="multipart/form-data">
                        <fieldset>
                            <div class="form-group">
                                <label class="control-label">名称</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" ng-model="model.name" name="name" required="true">
                            </div>


                            <div class="form-group">
                                <label for="exampleInputFile">文件</label>
                                <input type="file" id="exampleInputFile" name="video">
                                <p class="help-block">上传文件</p>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-default">Reset</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
@include('foot')
