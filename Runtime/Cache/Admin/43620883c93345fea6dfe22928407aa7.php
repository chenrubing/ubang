<?php if (!defined('THINK_PATH')) exit();?>
<ul id="editTree" class="ztree" style="min-height:300px">
</ul>
<div class="modal-ajax-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
<script type="text/javascript">
		var setting = { 
			async: {
				enable: true,
				url: "<?php echo U('JsonTree');?>",
				autoParam: ["id", "name=n", "level=lv"],
				otherParam: {"otherParam": "zTreeAsyncTest"},
				type: "get"
			},
			view: {
				dblClickExpand: false,
			},
			callback: {
				onClick: onClick
			}
		};
	
		function onClick(e,treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("editTree");
			zTree.expandNode(treeNode);
		}

		function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {
			curAsyncCount--;
			if (curAsyncCount <= 0) {
				curStatus = "";
				if (treeNode!=null) asyncForAll = true;
			}
		}
		
		function onClick(e, treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("editTree"),
			nodes = zTree.getSelectedNodes(),
			n = "";
			id = "";
			nodes.sort(function compare(a,b){return a.id-b.id;});
			for (var i=0, l=nodes.length; i<l; i++) {
				n += nodes[i].name + ",";
				id += nodes[i].id + ",";
			}
			if (n.length > 0 ) n = n.substring(0, n.length-1);
			if (id.length > 0 ) id = id.substring(0, id.length-1);
			var NameObj = $("#CategoryName");
			var PidObj = $("#CategoryPid");
			NameObj.html('<i class="icon-chevron-down"></i>'+n);
			PidObj.val(id);	
			
			$('#triggerModal').modal('toggle', 'false')
 
			
		}

		$(document).ready(function() {
			$.fn.zTree.init($("#editTree"), setting);
			
		});
	</script>