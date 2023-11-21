<?php

?>

<div class="row align-items-center justify-content-end EWestateReference-default-paging-row">
    <div class="col-auto EWestateReference-default-paging-col">
      <span class="EWestateReference-default-paging-descr">
        <?php if($list->rows==0){ ?>
          
        <?php }else{ ?>
          Einträge <strong><?=$list->record_from; ?> 
          bis <?=($list->record_to>$list->rows)?$list->rows:$list->record_to; ?></strong> 
          von <strong><?=$list->rows; ?></strong> 
          Treffern
        <?php } ?>
      </span>
    </div>

    <div class="col-auto EWestateReference-default-paging-col">

      <?php if($list->pages > 1){ ?>
        <nav aria-label="Pagination">
          <ul class="pagination ">
              <?php if((ExpowandDictionary::getPrevPage($list->page)) >= 1){ ?>
              <li class="page-item">
                <button type="button EWestateReference-default-paging-arrow-left" class="page-link" onclick="switch_page(<?=ExpowandDictionary::getPrevPage($list->page); ?>);"> <</button>
              </li>
              <?php } ?>
              <?php if((ExpowandDictionary::getPrevPage($list->page)-1) >= 1){ ?>
              <li class="page-item">
                <button type="button EWestateReference-default-paging-page-number" class="page-link" onclick="switch_page(<?=(ExpowandDictionary::getPrevPage($list->page)-1); ?>);"><?=(ExpowandDictionary::getPrevPage($list->page)-1); ?></button>
              </li>
              <?php } ?>
              <?php if(((ExpowandDictionary::getPrevPage($list->page)) >= 1) && ($list->page != 1)){ ?>
              <li class="page-item">
                <button type="button EWestateReference-default-paging-page-number" class="page-link" onclick="switch_page(<?=ExpowandDictionary::getPrevPage($list->page); ?>);"><?=ExpowandDictionary::getPrevPage($list->page); ?></button>
              </li>
              <?php } ?>
              <li class="page-item active" aria-current="page">
                <button type="button EWestateReference-default-paging-page-number" class="page-link" onclick="switch_page(<?=$list->page; ?>);"><?=$list->page; ?></button>
              </li>
              <?php if((ExpowandDictionary::getNextPage($list->page, $list->pages)) <= $list->pages){ ?>
              <li class="page-item">
                <button type="button EWestateReference-default-paging-page-number" class="page-link" onclick="switch_page(<?=ExpowandDictionary::getNextPage($list->page, $list->pages); ?>);"><?=ExpowandDictionary::getNextPage($list->page, $list->pages); ?></button>
              </li>
              <?php } ?>
              <?php if((ExpowandDictionary::getNextPage($list->page, $list->pages)+1) <= $list->pages){ ?>
              <li class="page-item">
              <button type="button EWestateReference-default-paging-page-number" class="page-link" onclick="switch_page(<?=(ExpowandDictionary::getNextPage($list->page, $list->pages)+1); ?>);"><?=(ExpowandDictionary::getNextPage($list->page, $list->pages)+1); ?></button>
              </li>
              <?php } ?>
              <?php if((ExpowandDictionary::getNextPage($list->page, $list->pages)) <= $list->pages){ ?>
              <li class="page-item">
                <button type="button EWestateReference-default-paging-arrow-right" class="page-link" onclick="switch_page(<?=ExpowandDictionary::getNextPage($list->page, $list->pages); ?>);"> ></button>
              </li>
              <?php } ?>
          </ul>
        </nav>
      <?php } ?>
    </div>
</div>

<script>

	function switch_page(page){
		document.getElementById("page_number").value = page;
		document.getElementById("form_search_offers").submit();
	}

</script>