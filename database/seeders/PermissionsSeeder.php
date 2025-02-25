<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions de gestion des utilisateurs
        $manageUsers = Permission::create(['name' => 'gérer utilisateurs']);
        Permission::create(['name' => 'voir utilisateurs', 'parent_id' => $manageUsers->id]);
        Permission::create(['name' => 'ajouter utilisateurs', 'parent_id' => $manageUsers->id]);
        Permission::create(['name' => 'editer utilisateurs', 'parent_id' => $manageUsers->id]);
        Permission::create(['name' => 'suspendre/réactiver utilisateurs', 'parent_id' => $manageUsers->id]);

        #New
        Permission::create(['name' => 'supprimer utilisateur admin', 'parent_id' => $manageUsers->id]);

        // Permissions de gestion des rôles
        $manageRoles = Permission::create(['name' => 'gérer rôles']);
        Permission::create(['name' => 'voir rôles', 'parent_id' => $manageRoles->id]);
        Permission::create(['name' => 'créer rôles', 'parent_id' => $manageRoles->id]);
        Permission::create(['name' => 'editer rôles', 'parent_id' => $manageRoles->id]);
        Permission::create(['name' => 'supprimer rôles', 'parent_id' => $manageRoles->id]);
        Permission::create(['name' => 'désactiver/réactiver rôles rôles', 'parent_id' => $manageRoles->id]);

        // Permissions de gestion des annonces
        $manageAds = Permission::create(['name' => 'gérer annonces']);
        Permission::create(['name' => 'voir annonces', 'parent_id' => $manageAds->id]);
        Permission::create(['name' => 'suspendre/réactiver annonces', 'parent_id' => $manageAds->id]);

        // Permissions de gestion des catégories
        $manageCategories = Permission::create(['name' => 'gérer catégories']);
        Permission::create(['name' => 'voir catégories', 'parent_id' => $manageCategories->id]);
        Permission::create(['name' => 'créer catégories', 'parent_id' => $manageCategories->id]);
        Permission::create(['name' => 'editer catégories', 'parent_id' => $manageCategories->id]);
        Permission::create(['name' => 'supprimer catégories', 'parent_id' => $manageCategories->id]);
        Permission::create(['name' => 'désactiver/réactiver catégories', 'parent_id' => $manageCategories->id]);

        // Permissions des paramètres de catégories
        $manageCategorySettings = Permission::create(['name' => 'gérer paramètres catégories']);
        Permission::create(['name' => 'voir paramètres catégories', 'parent_id' => $manageCategorySettings->id]);
        Permission::create(['name' => 'ajouter paramètres catégories', 'parent_id' => $manageCategorySettings->id]);
        Permission::create(['name' => 'editer paramètres catégories', 'parent_id' => $manageCategorySettings->id]);
        Permission::create(['name' => 'supprimer paramètres catégories', 'parent_id' => $manageCategorySettings->id]);

        // Permissions des modèles d'abonnements
        $manageSubscriptionModels = Permission::create(['name' => 'gérer modèles d\'abonnements']);
        Permission::create(['name' => 'voir modèles d\'abonnements', 'parent_id' => $manageSubscriptionModels->id]);
        Permission::create(['name' => 'créer modèles d\'abonnements', 'parent_id' => $manageSubscriptionModels->id]);
        Permission::create(['name' => 'editer modèles d\'abonnements', 'parent_id' => $manageSubscriptionModels->id]);
        Permission::create(['name' => 'supprimer modèles d\'abonnements', 'parent_id' => $manageSubscriptionModels->id]);

        // Permissions des paramètres de modèles d'abonnements
        $manageSubscriptionSettings = Permission::create(['name' => 'gérer paramètres modèles d\'abonnements']);
        Permission::create(['name' => 'voir paramètres modèles d\'abonnements', 'parent_id' => $manageSubscriptionSettings->id]);
        Permission::create(['name' => 'ajouter paramètres modèles d\'abonnements', 'parent_id' => $manageSubscriptionSettings->id]);
        Permission::create(['name' => 'editer paramètres modèles d\'abonnements', 'parent_id' => $manageSubscriptionSettings->id]);
        Permission::create(['name' => 'supprimer paramètres modèles d\'abonnements', 'parent_id' => $manageSubscriptionSettings->id]);

        // Permissions des transactions et abonnements
        $manageTransactions = Permission::create(['name' => 'gérer transactions/abonnements']);
        Permission::create(['name' => 'voir transactions/abonnements', 'parent_id' => $manageTransactions->id]);

        // Permissions de gestion des tickets de support
        $manageTickets = Permission::create(['name' => 'gérer tickets']);
        Permission::create(['name' => 'voir tickets', 'parent_id' => $manageTickets->id]);
        Permission::create(['name' => 'répondre tickets', 'parent_id' => $manageTickets->id]);
        Permission::create(['name' => 'clôturer tickets', 'parent_id' => $manageTickets->id]);

        // Permissions de gestion des catégories de tickets
        $manageCategoriesTicket = Permission::create(['name' => 'gérer catégories ticket']);
        Permission::create(['name' => 'voir catégories ticket', 'parent_id' => $manageCategoriesTicket->id]);
        Permission::create(['name' => 'créer catégories ticket', 'parent_id' => $manageCategoriesTicket->id]);
        Permission::create(['name' => 'editer catégories ticket', 'parent_id' => $manageCategoriesTicket->id]);
        Permission::create(['name' => 'supprimer catégories ticket', 'parent_id' => $manageCategoriesTicket->id]);
        Permission::create(['name' => 'désactiver/réactiver catégories ticket', 'parent_id' => $manageCategoriesTicket->id]);

        //Permissions de gestion de signalement
        $manageSignalement = Permission::create(['name' => 'gérer les signalements']);
        Permission::create(['name' => 'voir annonces signalées', 'parent_id' => $manageSignalement->id]);
        Permission::create(['name' => 'voir signalements d\'une annonce', 'parent_id' => $manageSignalement->id]);
    }
}
