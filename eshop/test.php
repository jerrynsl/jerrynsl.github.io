<?php
if ($_POST) {
    for ($x = 0; $x < count($_POST['product']); $x++) {
        if ($_POST['product'][$x] === '' && $_POST['quantity'][$x] === '') {
            unset($_POST['product'][$x]);
            unset($_POST['quantity'][$x]);
        }
    }

    var_dump($_POST['product']);
    var_dump($_POST['quantity']);
}


?>

<form method="POST">
    <?php
    $product_count = $_POST ? count($_POST['product']) : 1;
    for ($x = 0; $x < $product_count; $x++) {
    ?>
        <div class='pRow'>
            <select name='product[]'>
                <option value=''></option>
                <option value='a'>a</option>
                <option value='b'>b</option>
                <option value='c'>c</option>
            </select>
            <select name='quantity[]'>
                <option value=''></option>
                <option value='1'>1</option>
                <option value='2'>2</option>
                <option value='3'>3</option>
            </select>
        </div>
    <?php
    }
    ?>
    <button type='submit'>Submit</button>


    <input type='button' value='Add More' class='add_one btn btn-primary' />
    <input type='button' value='Delete' class='delete_one btn btn-danger' />



</form>

<!-- end .container -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

<script>
    document.addEventListener('click', function(event) {
        if (event.target.matches('.add_one')) {
            var element = document.querySelector('.pRow');
            var clone = element.cloneNode(true);
            element.after(clone);
        }
        if (event.target.matches('.delete_one')) {
            var total = document.querySelectorAll('.pRow').length;
            if (total > 1) {
                var element = document.querySelector('.pRow');
                element.remove(element);
            }
        }
    }, false);
</script>