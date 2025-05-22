<?php
class BaseBrzTwigController extends TwigBaseController
{
    public function getContext(): array
    {
        $context = parent::getContext();

        $query = $this->pdo->query("SELECT DISTINCT type FROM brz_cars ORDER BY 1");
        $types = $query->fetchAll();
        $context['types'] = $types;

        $query = $this->pdo->query("SELECT id, name, image FROM brz_cars_types");
        $types_bd = $query->fetchAll();
        $context['types_bd'] = $types_bd;

        return $context;
    }
}
