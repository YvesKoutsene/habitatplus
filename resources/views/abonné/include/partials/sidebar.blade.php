<aside class="bg-white shadow p-4 rounded sidebar" style="max-width: 250px;" id="sidebar">
    <h5 class="text-primary text-center fw-bold mb-4">Filtrer et trier</h5> <!-- Affiner la Recherche -->
    <form action="" method="GET">
        <div class="mb-4">
            <label for="type" class="form-label text-secondary fw-semibold">Type d'annonce</label>
            <select name="type" id="type" class="form-select form-control-sm border-primary">
                <option value="" disabled selected>Choisissez un type</option>
                <option value="vente">Vente</option>
                <option value="location">Location</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="categorie" class="form-label text-secondary fw-semibold">Catégorie de bien</label>
            <select name="categorie" id="categorie" class="form-select form-control-sm border-primary">
                <option value="" disabled selected>Choisissez une catégorie</option>
                <option value="maison">Maison</option>
                <option value="terrain">Terrain</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="localisation" class="form-label text-secondary fw-semibold">Localisation</label>
            <input type="text" name="lieu" id="lieu" class="form-control form-control-sm border-primary" placeholder="Taper un lieu de référence">
        </div>
        <div class="mb-4">
            <label for="prix_min" class="form-label text-secondary fw-semibold">Prix (CFA)</label>
            <div class="row">
                <div class="col-6">
                    <input type="number" name="prix_min" id="prix_min" class="form-control form-control-sm border-primary" placeholder="Min">
                </div>
                <div class="col-6">
                    <input type="number" name="prix_max" id="prix_max" class="form-control form-control-sm border-primary" placeholder="Max">
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-outline-primary btn-sm">Filtrer</button>
            <button type="reset" class="btn btn-outline-secondary btn-sm">Réinitialiser</button>
        </div>
    </form>
</aside>

<style>
    /* Sidebar Styling */
    #sidebar {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    #sidebar:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }

    #sidebar h3 {
        font-size: 1.5rem;
        color: #007bff;
    }

    /* Input and Select Styling */
    .form-select, .form-control {
        background-color: #fff;
        border-radius: 8px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    /* Button Styling */
    .btn {
        font-size: 0.9rem;
        padding: 8px 15px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    /* Responsive Design */
    @media (max-width: 576px) {
        #sidebar {
            max-width: 100%;
            padding: 20px;
        }

        #sidebar h3 {
            font-size: 1.25rem;
        }
    }
</style>
