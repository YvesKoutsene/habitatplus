<aside class="bg-light p-3 sidebar" style="width: 250px;" id="sidebar">
    <h3 class="text-black-50 text-center">Filtrer</h3>
    <form action="" method="GET">
        <div class="mb-3">
            <label for="type" class="form-label text-black">Type de bien</label>
            <select name="type" id="type" class="form-select form-control-sm"> <!-- form-control-sm-->
                <option value="---">---</option>
                <option value="vente">Vente</option>
                <option value="location">Location</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label text-black">Catégorie de bien</label>
            <select name="type" id="type" class="form-select form-control-sm"> <!-- form-control-sm-->
                <option value="---">---</option>
                <option value="maison">Maison</option>
                <option value="terrain">Terrain</option>
            </select>
        </div>

        <div class="mb-3">
            <div class="row">
                    <label for="prix_min" class="form-label text-black">Prix</label>
                <div class="col-6">
                    <input type="number" name="prix_min" id="prix_min" class="form-control form-control-sm" placeholder="min"> <!-- form-control-sm-->
                </div>
                <div class="col-6">
                    <input type="number" name="prix_max" id="prix_max" class="form-control form-control-sm" placeholder="max"> <!-- form-control-sm-->
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary btn-sm ">Valider</button> <!-- btn-sm-->
        </div>
    </form>
</aside>
