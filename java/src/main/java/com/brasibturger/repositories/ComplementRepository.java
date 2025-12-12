package com.brasibturger.repositories;

import com.brasibturger.models.Complement;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import java.util.List;

@Repository
public interface ComplementRepository extends JpaRepository<Complement, Long> {
    List<Complement> findByStatut(String statut);
    List<Complement> findByTypeComplement(String typeComplement);
    List<Complement> findByNomContainingIgnoreCase(String nom);
}
