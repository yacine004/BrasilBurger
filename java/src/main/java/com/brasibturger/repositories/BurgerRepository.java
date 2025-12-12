package com.brasibturger.repositories;

import com.brasibturger.models.Burger;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import java.util.List;

@Repository
public interface BurgerRepository extends JpaRepository<Burger, Long> {
    List<Burger> findByStatut(String statut);
    List<Burger> findByNomContainingIgnoreCase(String nom);
}
