package com.brasibturger.repositories;

import com.brasibturger.models.Livreur;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import java.util.List;

@Repository
public interface LivreurRepository extends JpaRepository<Livreur, Long> {
    List<Livreur> findByZoneIdZone(Long zoneId);
    List<Livreur> findByStatut(String statut);
}
