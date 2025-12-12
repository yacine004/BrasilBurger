package com.brasibturger.repositories;

import com.brasibturger.models.Menu;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import java.util.List;

@Repository
public interface MenuRepository extends JpaRepository<Menu, Long> {
    List<Menu> findByStatut(String statut);
    List<Menu> findByNomContainingIgnoreCase(String nom);
}
