package com.brasibturger.repositories;

import com.brasibturger.models.Commande;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import java.time.LocalDateTime;
import java.util.List;

@Repository
public interface CommandeRepository extends JpaRepository<Commande, Long> {
    List<Commande> findByClientIdClient(Long clientId);
    List<Commande> findByEtat(String etat);
    List<Commande> findByTypeLivraison(String typeLivraison);
    List<Commande> findByDateCreationAfter(LocalDateTime date);
    List<Commande> findByClientIdClientAndEtat(Long clientId, String etat);
}
