<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="alert alert-info"><a href="<?php echo $refresh; ?>" data-toggle="tooltip" class="btn btn-info"><i class="fa fa-refresh"></i> <?php echo $button_refresh; ?></a>
    </div>
    <?php } ?>
    <?php if (!empty($error)) { ?>
      <?php foreach($error as $error_message) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_message; ?></div>
      <?php } ?>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_dashboard; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ocmod">
        <div class="row">
            <?php if(isset($name) && isset($code) && isset($author) && isset($xml)){ ?>
                <div class="col-md-6">
                    <label>Название</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $name; ?>">
                </div>
                <div class="col-md-6">
                    <label>Идентификатор</label>
                    <input type="text" readonly name="modification_id" id="modification_id" class="form-control" value="<?php echo $modification_id; ?>">
                </div>
                <div class="col-md-6">
                    <label>Код</label>
                    <input type="text" name="code" id="code" class="form-control" value="<?php echo $code; ?>">
                </div>
                <div class="col-md-6">
                    <label>Автор</label>
                    <input type="text" name="author" id="author" class="form-control" value="<?php echo $author; ?>">
                </div>
                <div class="col-md-6">
                    <label>Версия</label>
                    <input type="text" name="version" id="version" class="form-control" value="<?php echo $version; ?>">
                </div>
                <div class="col-md-6">
                    <label>Статус</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" <?php echo $status=="1"?"selected":""; ?>>Включен</option>
                        <option value="0" <?php echo $status=="0"?"selected":""; ?>>Отключен</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label>Ссылка</label>
                    <input type="text" name="link" id="link" class="form-control" value="<?php echo $link; ?>">
                </div>
                <div class="col-md-12">
                    <label>XML</label>
                    <textarea contenteditable="true" name="xml" id="xml" cols="20" rows="30" class="form-control" style="width: 100%"><?php echo $xml; ?></textarea><br>
                </div>
                <div class="col-md-12">
                    <label>&nbsp;</label>
                    <div class="btn-group pull-right">
                        <a href="<?php echo $button_cancel; ?>" class="btn btn-default"><?php echo $text_cancel; ?></a>
                        <input type="submit" class="btn btn-primary" value="<?php echo $text_save; ?>">
                    </div>
                </div>
            <?php }else{ ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_no_mod; ?></div>
            <?php } ?>  
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>