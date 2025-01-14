<aside class="bg-light p-3">
    <h3 class="text-black text-center">Filtrer</h3>
    <form action="{{ url('/recherche') }}" method="GET">
        <div class="mb-3">
            <label for="type" class="form-label text-black">Type de bien</label>
            <select name="type" id="type" class="form-select">
                <option value="vente">Vente</option>
                <option value="location">Location</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label text-black">Catégorie de bien</label>
            <select name="type" id="type" class="form-select">
                <option value="maison">Maison</option>
                <option value="terrain">Terrain</option>
                <option value="---">---</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="prix_min" class="form-label text-black">Prix min</label>
            <input type="number" name="prix_min" id="prix_min" class="form-control">
        </div>

        <div class="mb-3">
            <label for="prix_max" class="form-label text-black">Prix max</label>
            <input type="number" name="prix_max" id="prix_max" class="form-control">
        </div>

        <div class="d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-primary">Valider</button>
            <button type="reset" class="btn btn-danger">Réinitialiser</button>
        </div>
    </form>
</aside>
