const deleteProduct = document.querySelectorAll('.deleteproduct');

for (i = 0, len = deleteProduct.length; i < len; i++) {
	deleteProduct[i].onclick = function() {
		return confirm('Do you want to delete this element?');
	}
}